# -*- coding: utf-8 -*-
import kivy
from kivy.animation import Animation
from kivy.app import App
from kivy.clock import Clock
from kivy.core import window
from kivy.lang import Builder
from kivy.properties import ObjectProperty
from kivy.uix.behaviors import ButtonBehavior
from kivy.uix.floatlayout import FloatLayout
from kivy.uix.image import Image
from kivy.uix.screenmanager import Screen, ScreenManager
from kivy_classes.ElementsMisc import ImageLeft, ImageRight, LockLineTop, LockLineBot
from configs.config import config_flush_time, config_absolute_time, config_relative_time, config_inhale_time, config_exhale_time
from kivy_classes.ElementsStylesStrings import main_background, welcome_title_text, welcome_error_login_text, welcome_text_input_back_color, welcome_error_login_text_color, loading_title_text, loading_message_text, loading_load_text_absolute_text, loading_load_text_finish_text, loading_load_text_flush_text, loading_load_text_relative_text, \
    main_exhale_text, main_inhale_text, main_intro_text, main_exhale_end_text, main_inhale_end_text, save_load_flushing, save_load_saving, save_load_title_text, save_load_message_text
from os import listdir
import os
import sys
import csv
import errno
import time
import subprocess
# import RPi.GPIO as GPIO

kivy.require('1.10.0')
kv_path = './kivy_resources/'
for kv in listdir(kv_path):
    Builder.load_file(kv_path + kv)

# To be remove its just for testing. RPI display exact size
SIZE = 1
FORMAT = (800, 480)
window.Window.size = [SIZE * iSize for iSize in FORMAT]
window.Window.clearcolor = [0.2, 0.2, 0.2, 1]

# Pump and valve operations, variables and methods
pump_on = False
valve_on = False
fan_on = False
# Pins
valve_pin = 13
pump_pin = 20
fan_pin = 21


# TEST
class GPIOTest:
    HIGH = True
    LOW = False
    BCM = True
    OUT = True

    def __init__(self):
        pass

    @staticmethod
    def setmode(bcm):
        print 'BCM mode is set to: ' + str(bcm)

    @staticmethod
    def setup(pin, out):
        name = ''
        if pin == 13:
            name = 'Valve'
        elif pin == 20:
            name = 'Pump'
        elif pin == 21:
            name = 'Fan'
        print name + ' mode is set to OUT: ' + str(out)

    @staticmethod
    def output(pin, pin_state):
        name = ''
        if pin == 13:
            name = 'Valve'
        elif pin == 20:
            name = 'Pump'
        elif pin == 21:
            name = 'Fan'
        print name + ' is ' + str(pin_state)

GPIO = GPIOTest()


def init_gpio():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(valve_pin, GPIO.OUT)
    GPIO.setup(pump_pin, GPIO.OUT)
    GPIO.setup(fan_pin, GPIO.OUT)


def pump_operation(is_on):
    global pump_on
    if is_on:
        if pump_on:
            print 'Pump is already ON'
        else:
            GPIO.output(pump_pin, GPIO.HIGH)
            pump_on = True
    else:
        if pump_on:
            GPIO.output(pump_pin, GPIO.LOW)
            pump_on = False
        else:
            print 'Pump is already OFF'


def valve_operation(is_on):
    global valve_on
    if is_on:
        if valve_on:
            print 'Valve is already ON'
        else:
            GPIO.output(valve_pin, GPIO.HIGH)
            valve_on = True
    else:
        if valve_on:
            GPIO.output(valve_pin, GPIO.LOW)
            valve_on = False
        else:
            print 'Valve is already OFF'


def fan_operation(is_on):
    global fan_on
    if is_on:
        if fan_on:
            print 'Fan is already ON'
        else:
            GPIO.output(fan_pin, GPIO.LOW)
            fan_on = True
    else:
        if fan_on:
            GPIO.output(fan_pin, GPIO.HIGH)
            fan_on = False
        else:
            print 'Fan is already OFF'


def transition_lock():
    sm.current = 'lock_screen'
    Clock.schedule_once(lambda l: lock_screen.animate_lock(), .5)


class LockScreen(Screen):
    animation_button_press = Animation(size_hint=[.15, .15], duration=.1) + Animation(size_hint=[.2, .2], duration=.1)
    animation_unlock_button = Animation(angle=0, duration=.5)
    animation_lock_button = Animation(angle=360, duration=.5)
    animation_show_element = Animation(opacity=1, duration=.3)
    animation_hide_element = Animation(opacity=0, duration=.3)
    animation_left_unlock = Animation(pos_hint={'center_x': -1, 'center_y': .5})
    animation_right_unlock = Animation(pos_hint={'center_x': 2, 'center_y': .5})
    animation_button_unlock = Animation(pos_hint={'center_x': 1.75, 'center_y': .5})
    animation_left_lock = Animation(pos_hint={'center_x': .246, 'center_y': .5})
    animation_right_lock = Animation(pos_hint={'center_x': .75, 'center_y': .5})
    animation_button_lock = Animation(pos_hint={'center_x': .5, 'center_y': .5})

    def start_unlock_animation(self):
        self.animation_button_press.start(self.ids.unlock_button)
        Clock.schedule_once(lambda l: self.animate_lock_unlock_spin(True), .5)
        Clock.schedule_once(lambda l: self.animate_show_line_unlock(), 1.5)
        Clock.schedule_once(lambda l: self.animate_unlock(), 2.5)
        Clock.schedule_once(lambda l: self.proceed_to_login_screen(), 3)

    def animate_lock_unlock_spin(self, is_unlock):
        if is_unlock:
            self.ids.unlock_button.angle = 359
            self.animation_unlock_button.start(self.ids.unlock_button)
        else:
            self.ids.unlock_button.angle = 0
            self.animation_lock_button.start(self.ids.unlock_button)

    def animate_show_line_unlock(self):
        self.animation_show_element.start(lock_line_bot)
        self.animation_show_element.start(lock_line_top)

    def animate_show_line_lock(self):
        lock_line_bot.opacity = 1
        self.animation_hide_element.start(lock_line_bot)
        lock_line_top.opacity = 1
        self.animation_hide_element.start(lock_line_top)

    def animate_unlock(self):
        self.animation_left_unlock.start(image_left)
        lock_line_bot.opacity = 0
        lock_line_top.opacity = 0
        self.animation_right_unlock.start(image_right)
        self.animation_button_unlock.start(self.ids.unlock_button)
        Clock.schedule_once(lambda l: self.set_elements_opacity(0), .5)

    def animate_lock(self):
        self.set_elements_opacity(1)
        self.animation_left_lock.start(image_left)
        Clock.schedule_once(lambda l: self.animate_show_line_lock(), 1)
        Clock.schedule_once(lambda l: self.animate_lock_unlock_spin(False), 1.5)
        self.animation_right_lock.start(image_right)
        self.animation_button_lock.start(self.ids.unlock_button)
        Clock.schedule_once(lambda l: self.set_button_disable_state(False), 2)

    def set_elements_opacity(self, num):
        image_left.opacity = num
        image_right.opacity = num
        self.ids.unlock_button.opacity = num

    def set_button_disable_state(self, is_disabled):
        self.ids.unlock_button.disabled = is_disabled

    def button_action(self):
        self.start_unlock_animation()
        self.set_button_disable_state(True)

    @staticmethod
    def proceed_to_login_screen():
        sm.current = 'login_screen'
        Clock.schedule_once(lambda l: login_screen.start_end_login_animation(True), .5)


class LoginScreen(Screen):
    current_user_id = None
    current_first_name = None
    current_last_name = None

    title_text = welcome_title_text
    error_login_text = welcome_error_login_text
    error_login_text_color = welcome_error_login_text_color
    login_text_back_color = welcome_text_input_back_color

    animation_button_press = Animation(size_hint=[.12, .12], duration=.1) + Animation(size_hint=[.15, .15], duration=.1)
    animation_label_up = Animation(pos_hint={'center_x': .5, 'center_y': .9}, duration=.5)
    animation_label_down = Animation(pos_hint={'center_x': .5, 'center_y': .75}, duration=.5)
    animation_button_up = Animation(pos_hint={'center_x': .5, 'center_y': .25}, duration=.5)
    animation_button_down = Animation(pos_hint={'center_x': .5, 'center_y': .1}, duration=.5)
    animation_elements_opacity_show = Animation(opacity=1, duration=.3)
    animation_elements_opacity_hide = Animation(opacity=0, duration=.3)

    def start_end_login_animation(self, is_start):
        if is_start:
            self.animation_show_hide_button(is_start)
            self.animation_show_hide_label(is_start)
            Clock.schedule_once(lambda l: self.animation_show_hide_text_input(is_start), .5)
            Clock.schedule_once(lambda l: self.animation_show_hide_lock_button(is_start), 1)
            Clock.schedule_once(lambda l: self.lock_unlock_buttons(False), 1.5)
        else:
            self.animation_show_hide_lock_button(is_start)
            Clock.schedule_once(lambda l: self.animation_show_hide_text_input(is_start), .5)
            Clock.schedule_once(lambda l: self.animation_show_hide_error_text(is_start), .5)
            Clock.schedule_once(lambda l: self.animation_show_hide_button(is_start), 1)
            Clock.schedule_once(lambda l: self.animation_show_hide_label(is_start), 1)

    def animation_show_hide_label(self, is_show):
        if is_show:
            self.animation_label_down.start(self.ids.login_label)
            self.animation_elements_opacity_show.start(self.ids.login_label)
        else:
            self.animation_label_up.start(self.ids.login_label)
            self.animation_elements_opacity_hide.start(self.ids.login_label)

    def animation_show_hide_button(self, is_show):
        if is_show:
            self.animation_button_up.start(self.ids.login_button)
            self.animation_elements_opacity_show.start(self.ids.login_button)
        else:
            self.animation_button_down.start(self.ids.login_button)
            self.animation_elements_opacity_hide.start(self.ids.login_button)

    def animation_show_hide_text_input(self, is_show):
        if is_show:
            self.animation_elements_opacity_show.start(self.ids.login_input)
            self.animation_elements_opacity_show.start(self.ids.login_text_back)
        else:
            self.animation_elements_opacity_hide.start(self.ids.login_input)
            self.animation_elements_opacity_hide.start(self.ids.login_text_back)

    def animation_show_hide_error_text(self, is_show):
        if is_show:
            self.animation_elements_opacity_show.start(self.ids.login_error_label)
        else:
            self.animation_elements_opacity_hide.start(self.ids.login_error_label)

    def animation_show_hide_lock_button(self, is_show):
        if is_show:
            self.animation_elements_opacity_show.start(self.ids.login_lock_button)
        else:
            self.animation_elements_opacity_hide.start(self.ids.login_lock_button)

    def animation_login_button_press(self):
        self.lock_unlock_buttons(True)
        self.animation_button_press.start(self.ids.login_button)
        Clock.schedule_once(lambda l: self.login_button_action(), .3)

    def animation_lock_button_press(self):
        self.lock_unlock_buttons(True)
        self.animation_button_press.start(self.ids.login_lock_button)
        Clock.schedule_once(lambda l: self.lock_button_action(), .3)

    def login_button_action(self):
        self.check_id()

    def lock_button_action(self):
        self.start_end_login_animation(False)
        self.lock_unlock_buttons(True)
        Clock.schedule_once(lambda l: transition_lock(), 1.5)
        Clock.schedule_once(lambda l: self.reset_screen(), 1)

    def proceed_to_loading_screen(self, name):
        sm.current = 'loading_screen'
        Clock.schedule_once(lambda l: loading_screen.init(name), .5)  # name should be changed
        Clock.schedule_once(lambda l: self.reset_screen(), 1)

    def reset_screen(self):
        self.ids.login_input.text = ''

    def lock_unlock_buttons(self, lock):
        self.ids.login_button.disabled = lock
        self.ids.login_lock_button.disabled = lock

    def check_id(self):
        result = self.check_id_csv()
        if result is not None:
            self.start_end_login_animation(False)
            Clock.schedule_once(lambda l: self.proceed_to_loading_screen(str(self.get_current_user()[1])), 1.5)
            self.lock_unlock_buttons(True)
        else:
            self.animation_show_hide_error_text(True)
            self.lock_unlock_buttons(False)
            Clock.schedule_once(lambda l: self.animation_show_hide_error_text(False), 1.5)

    def check_id_csv(self):
        with open('./configs/id_list.csv') as csv_file:
            id_list = csv.reader(csv_file, delimiter=',', quotechar='|')
            for row in id_list:
                if row[1] == self.ids.login_input.text:
                    self.set_current_user(row[1], row[0].split()[0], row[0].split()[1])
                    return row[0]
            return None

    def set_current_user(self, user_id, first_name, last_name):
        self.current_user_id = user_id
        self.current_first_name = first_name
        self.current_last_name = last_name

    def get_current_user(self):
        return [self.current_user_id, self.current_first_name, self.current_last_name]

    def reset_current_user(self):
        self.current_user_id = None
        self.current_first_name = None
        self.current_last_name = None


class LoadingScreen(Screen):
    value = 0
    pb_loop = None
    state = 1
    loading_animation_duration = .5
    flushing_time = config_flush_time
    absolute_zeros_time = config_absolute_time
    relative_zeros_time = config_relative_time
    # animations for start of loading
    animation_start_bot_line = Animation(points=(100, 150, 700, 150), duration=loading_animation_duration)
    animation_start_top_line = Animation(points=(100, 190, 700, 190), duration=loading_animation_duration)
    animation_start_left_line = Animation(points=(100, 150, 100, 190), duration=loading_animation_duration)
    animation_start_right_line = Animation(points=(700, 150, 700, 190), duration=loading_animation_duration)
    animation_start_load_bar = Animation(pos=[100, 150], duration=loading_animation_duration)
    animation_start_show = Animation(opacity=1, duration=loading_animation_duration)
    animation_start_title_label = Animation(pos_hint={'center_x': .5, 'center_y': .8}, duration=loading_animation_duration)

    # animations for end of loading
    animation_finish_bot_line = Animation(points=(100, 140, 700, 140), duration=loading_animation_duration)
    animation_finish_top_line = Animation(points=(100, 180, 700, 180), duration=loading_animation_duration)
    animation_finish_left_line = Animation(points=(100, 140, 100, 180), duration=loading_animation_duration)
    animation_finish_right_line = Animation(points=(700, 140, 700, 180), duration=loading_animation_duration)
    animation_finish_load_bar = Animation(pos=[100, 140], duration=loading_animation_duration)
    animation_finish_hide = Animation(opacity=0, duration=loading_animation_duration)
    animation_finish_title_label = Animation(pos_hint={'center_x': .5, 'center_y': .85}, duration=loading_animation_duration)

    # Flushing starts here
    def init(self, user_name):
        self.ids.init_title_label.text = loading_title_text + user_name
        self.ids.init_message_label.text = loading_message_text
        self.reset_load_bar()
        self.move_loading_bar(False)
        self.show_hide_loading_bar(False)
        Clock.schedule_once(lambda l: self.animate_title_label(True), .5)
        Clock.schedule_once(lambda l: self.animate_message_label(True), .5)
        Clock.schedule_once(lambda l: self.animate_load_bar(True), .5)
        Clock.schedule_once(lambda l: self.animate_loading_label(True), 1)
        Clock.schedule_once(lambda l: self.start_loading_loop(self.flushing_time), 2.5)
        Clock.schedule_once(lambda l: self.flush_operation(), 2.5)

    def start_loading_loop(self, time_value):
        self.pb_loop = Clock.schedule_interval(lambda l: self.load_bar(), time_value / 100.0)

    # Absolute and relative zeros are here
    def load_bar(self):
        if self.value < 101:
            self.ids.init_loading_bar.set_value(self.value)
            if self.state == 1:
                self.ids.init_loading_label.text = loading_load_text_flush_text + str(self.value) + '%'
            elif self.state == 2:
                self.ids.init_loading_label.text = loading_load_text_absolute_text + str(self.value) + '%'
            else:
                self.ids.init_loading_label.text = loading_load_text_relative_text + str(self.value) + '%'
            self.value += 1
        else:
            self.pb_loop.cancel()
            self.pb_loop = None
            if self.state == 1:
                self.ids.init_loading_label.text = loading_load_text_flush_text + loading_load_text_finish_text
            elif self.state == 2:
                self.ids.init_loading_label.text = loading_load_text_absolute_text + loading_load_text_finish_text
            else:
                self.ids.init_loading_label.text = loading_load_text_relative_text + loading_load_text_finish_text
            self.value += 1
            Clock.schedule_once(lambda l: self.animate_load_bar(False), self.loading_animation_duration * 2)
            Clock.schedule_once(lambda l: self.animate_loading_label(False), self.loading_animation_duration)
            Clock.schedule_once(lambda l: self.reset_load_bar(), 1.5)
            if self.state < 3:
                Clock.schedule_once(lambda l: self.animate_load_bar(True), 2)
                Clock.schedule_once(lambda l: self.animate_loading_label(True), 3)
                if self.state == 1:
                    Clock.schedule_once(lambda l: self.start_loading_loop(self.absolute_zeros_time), 4)
                    self.absolute_zeros_operation()
                    self.call_mode_absolute_zero()
                elif self.state == 2:
                    Clock.schedule_once(lambda l: self.start_loading_loop(self.relative_zeros_time), 4)
                    self.relative_zeros_operation()
                    self.call_mode_relative_zero()
                self.state += 1
            else:
                self.state = 1
                Clock.schedule_once(lambda l: self.animate_title_label(False), 1)
                Clock.schedule_once(lambda l: self.animate_message_label(False), 1)
                Clock.schedule_once(lambda l: self.proceed_to_main_screen(), 2)

    def reset_load_bar(self):
        self.value = 0
        self.ids.init_loading_bar.set_value(self.value)
        if self.state == 1:
            self.ids.init_loading_label.text = loading_load_text_flush_text + str(self.value) + '%'
        elif self.state == 2:
            self.ids.init_loading_label.text = loading_load_text_absolute_text + str(self.value) + '%'
        else:
            self.ids.init_loading_label.text = loading_load_text_relative_text + str(self.value) + '%'

    def animate_load_bar(self, start):
        if start:
            self.animation_start_bot_line.start(self.ids.init_loading_bar.bot_line)
            self.animation_start_top_line.start(self.ids.init_loading_bar.top_line)
            self.animation_start_left_line.start(self.ids.init_loading_bar.left_line)
            self.animation_start_right_line.start(self.ids.init_loading_bar.right_line)
            self.animation_start_load_bar.start(self.ids.init_loading_bar.load_bar)
            self.animation_start_show.start(self.ids.init_loading_bar)
        else:
            self.animation_finish_bot_line.start(self.ids.init_loading_bar.bot_line)
            self.animation_finish_top_line.start(self.ids.init_loading_bar.top_line)
            self.animation_finish_left_line.start(self.ids.init_loading_bar.left_line)
            self.animation_finish_right_line.start(self.ids.init_loading_bar.right_line)
            self.animation_finish_load_bar.start(self.ids.init_loading_bar.load_bar)
            self.animation_finish_hide.start(self.ids.init_loading_bar)

    def animate_title_label(self, start):
        if start:
            self.animation_start_title_label.start(self.ids.init_title_label)
            self.animation_start_show.start(self.ids.init_title_label)
        else:
            self.animation_finish_title_label.start(self.ids.init_title_label)
            self.animation_finish_hide.start(self.ids.init_title_label)

    def animate_message_label(self, start):
        if start:
            self.animation_start_show.start(self.ids.init_message_label)
        else:
            self.animation_finish_hide.start(self.ids.init_message_label)

    def animate_loading_label(self, start):
        if start:
            self.animation_start_show.start(self.ids.init_loading_label)
        else:
            self.animation_finish_hide.start(self.ids.init_loading_label)

    def move_loading_bar(self, up):
        if up:
            self.ids.init_loading_bar.bot_line.points = (100, 150, 700, 150)
            self.ids.init_loading_bar.top_line.points = (100, 190, 700, 190)
            self.ids.init_loading_bar.left_line.points = (100, 150, 100, 190)
            self.ids.init_loading_bar.right_line.points = (700, 150, 700, 190)
            self.ids.init_loading_bar.load_bar.pos = [100, 150]
        else:
            self.ids.init_loading_bar.bot_line.points = (100, 140, 700, 140)
            self.ids.init_loading_bar.top_line.points = (100, 180, 700, 180)
            self.ids.init_loading_bar.left_line.points = (100, 140, 100, 180)
            self.ids.init_loading_bar.right_line.points = (700, 140, 700, 180)
            self.ids.init_loading_bar.load_bar.pos = [100, 140]

    def show_hide_loading_bar(self, show):
        if show:
            self.ids.init_loading_bar.opacity = 1
        else:
            self.ids.init_loading_bar.opacity = 0

    @staticmethod
    def proceed_to_main_screen():
        sm.current = 'main_screen'
        Clock.schedule_once(lambda l: main_screen.init(), .5)

    def reset_screen(self): # WIP
        self.ids.login_input.text = ''
        self.ids.login_button.disabled = False

    @staticmethod
    def flush_operation():
        pump_operation(True)
        valve_operation(True)

    @staticmethod
    def absolute_zeros_operation():
        pump_operation(True)
        valve_operation(True)

    @staticmethod
    def relative_zeros_operation():
        pump_operation(True)
        valve_operation(False)

    @staticmethod
    def call_mode_absolute_zero():
        subprocess.Popen([sys.executable, './scripts/mode1.py'], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)

    @staticmethod
    def call_mode_relative_zero():
        subprocess.Popen([sys.executable, './scripts/mode2.py'], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)


class ScreenMain(Screen):
    # Elements for loading circle bar: value - percentage unit, load_loop - Clock() object that updates value and therefore animates loading, not_loading - boolean for starting animation and making sure it doesn't start again
    value = 0
    load_loop = None
    not_loading = True

    # Timers for inhale/exhale (should be gathered from a config file) and boolean to distinguish the state of the process if its either inhale or the exhale time for count
    inhale_time = config_inhale_time
    exhale_time = config_exhale_time
    is_inhale = True

    # All the animation objects for the elements of the screen
    animation_button_press = Animation(size_hint=[.15, .15], duration=.1) + Animation(size_hint=[.2, .2], duration=.1)
    button_move_down_animation = Animation(pos_hint={'center_x': .5, 'center_y': .3659}, duration=.5)
    button_move_up_animation = Animation(pos_hint={'center_x': .5, 'center_y': .4159}, duration=.5)
    title_move_up_animation = Animation(pos_hint={'center_x': .5, 'center_y': .8}, duration=.5)
    title_move_down_animation = Animation(pos_hint={'center_x': .5, 'center_y': .75}, duration=.5)
    show_animation = Animation(opacity=1, duration=.5)
    hide_animation = Animation(opacity=0, duration=.5)
    countdown_number_animation = Animation(font_size=100, opacity=1, duration=.1)
    countdown_text_animation = Animation(font_size=45, opacity=1, duration=.1)

    # Elements for a countdown, countdown_loop - Clock() object that starts numbers countdown, countdown_number - current number of a countdown
    countdown_loop = None
    countdown_number = None

    # Function for initiating the screen. Loads up and animates the title and button reveal as well as sets the text value of a title !!! Wait method is called here
    def init(self):
        self.wait_operation()
        self.set_title_text(main_intro_text)
        Clock.schedule_once(lambda l: self.animate_title_show_hide(True), .5)
        Clock.schedule_once(lambda l: self.animate_button_show_hide(True), .5)

    # Function to start countdown loop and call countdown function. Count down is started a second after this function is called
    def start_countdown(self, time_value):
        self.countdown_number = time_value + 1

        self.countdown_loop = Clock.schedule_interval(lambda l: self.countdown(time_value), 1)

    # Main countdown logic function. Initial countdown counting down the time before user have to hold his/her breath and wait before exhaling. Upon ending this function calls for a 'start_countdown' function to start counting down the timer for the user to exhale time argument is the time length in seconds for how long to counting !!!Exhale and Exhale methods are called here
    def countdown(self, time_value):
        if self.countdown_number > 0:
            self.countdown_number -= 1
            if self.countdown_number != 0:
                self.animate_count_number(str(self.countdown_number))
                if self.not_loading:
                    self.not_loading = False
                    self.countdown_loading_bar_start(time_value)
                if self.countdown_number == 1:
                    if self.is_inhale:
                        self.animate_title_show_hide(False)
                        Clock.schedule_once(lambda l: self.set_title_text(main_exhale_text), .6)
                    else:
                        self.animate_title_show_hide(False)
            else:
                self.countdown_loop.cancel()
                self.countdown_loop = None
                if self.is_inhale:
                    self.animate_count_text(main_inhale_end_text)
                    self.is_inhale = False
                    self.not_loading = True
                    Clock.schedule_once(lambda l: self.animate_title_show_hide(True), 0.7)
                    self.animate_loading_circle_show_hide(False)
                    # Start of the exhale countdown
                    Clock.schedule_once(lambda l: self.animate_loading_circle_show_hide(True), 1)
                    Clock.schedule_once(lambda l: self.start_countdown(self.exhale_time), 1)
                    # Starting reading values until the end of the count
                    # Clock.schedule_once(self.start_reading, 1)
                    Clock.schedule_once(lambda l: self.exhale_operation(), 1)
                    Clock.schedule_once(lambda l: self.call_mode_exhale(), 1)
                else:
                    self.animate_count_text(main_exhale_end_text)
                    self.is_inhale = True
                    self.not_loading = True
                    self.exhale_end_operation()
                    Clock.schedule_once(lambda l: self.animate_loading_circle_show_hide(False), 1)
                    Clock.schedule_once(lambda l: self.proceed_to_save_screen(), 1.5)
        else:
            self.ids.main_countdown_label.text = ''
            self.ids.main_countdown_label.opacity = 0
            self.countdown_loop.cancel()
            self.countdown_loop = None

    # Animation for the end of a count
    def animate_count_text(self, label_text):
        self.ids.main_countdown_label.font_size = 20
        self.ids.main_countdown_label.text = label_text
        self.countdown_text_animation.start(self.ids.main_countdown_label)
        Clock.schedule_once(lambda l: self.animate_count_hide_number(), .8)

    # Animation for showing up countdown number
    def animate_count_number(self, number):
        self.ids.main_countdown_label.text = number
        self.countdown_number_animation.start(self.ids.main_countdown_label)
        Clock.schedule_once(lambda l: self.animate_count_hide_number(), .8)

    # Animation for hiding countdown number after it appeared
    def animate_count_hide_number(self):
        self.ids.main_countdown_label.opacity = 0
        self.ids.main_countdown_label.font_size = 50
        self.ids.main_countdown_label.text = ''

    # Animation for title to be shown or hidden
    def animate_title_show_hide(self, show):
        if show:
            self.show_animation.start(self.ids.main_title_label)
            self.title_move_down_animation.start(self.ids.main_title_label)
        else:
            self.hide_animation.start(self.ids.main_title_label)
            self.title_move_up_animation.start(self.ids.main_title_label)

    # Animation for button to be shown or hidden
    def animate_button_show_hide(self, show):
        if show:
            self.show_animation.start(self.ids.main_button)
            self.button_move_up_animation.start(self.ids.main_button)
        else:
            self.button_move_down_animation.start(self.ids.main_button)
            self.hide_animation.start(self.ids.main_button)

    # Animation for loading circle bar to be shown or hidden
    def animate_loading_circle_show_hide(self, show):
        if show:
            self.ids.circle_bar.show_circles()
            Clock.schedule_once(lambda l: self.show_animation.start(self.ids.circle_bar), 1)
        else:
            self.hide_animation.start(self.ids.circle_bar)
            Clock.schedule_once(lambda l: self.ids.circle_bar.hide_circles(), 1)

    # Animation for a loading circle bar countdown
    def animate_countdown_loading_circle(self):
        if self.value < 101:
            self.ids.circle_bar.set_value(self.value)
            self.value += 1
        else:
            self.value = 0
            self.load_loop.cancel()
            self.load_loop = None

    # Animation for pressing a button (time length = 1.5 seconds)
    def animate_button_press_action(self):
        self.button_press_action()
        self.animation_button_press.start(self.ids.main_button)
        Clock.schedule_once(lambda l: self.animate_button_show_hide(False), .5)

    # Action upon press of a button !!! Inhale method is called here
    def button_press_action(self):
        self.ids.main_button.disabled = True

        # Animation to hide title, change the text and show it back again (time length = 1.5 seconds)
        self.animate_title_show_hide(False)
        Clock.schedule_once(lambda l: self.set_title_text(main_inhale_text), .5)
        Clock.schedule_once(lambda l: self.animate_title_show_hide(True), 1)

        # Animation to load circle loading bar (time length = 1.5)
        Clock.schedule_once(lambda l: self.animate_loading_circle_show_hide(True), 1.5)

        # Calling the method of starting countdown
        Clock.schedule_once(lambda l: self.start_countdown(self.inhale_time), 4)
        Clock.schedule_once(lambda l: self.inhale_operation(), 4)

    # Function to start countdown loop for loading circle bar
    def countdown_loading_bar_start(self, time_value):
        self.load_loop = Clock.schedule_interval(lambda l: self.animate_countdown_loading_circle(), time_value / 100.0)

    # Title label setter
    def set_title_text(self, title_text):
        self.ids.main_title_label.text = title_text

    def proceed_to_save_screen(self):
        self.ids.main_button.disabled = False
        sm.current = 'save_screen'
        Clock.schedule_once(lambda l: save_screen.init(), .5)
        Clock.schedule_once(lambda l: self.reset_screen(), 1)

    def reset_screen(self):
        self.ids.main_button.disabled = False

    @staticmethod
    def wait_operation():
        pump_operation(False)
        valve_operation(False)

    @staticmethod
    def inhale_operation():
        pump_operation(False)
        valve_operation(False)

    @staticmethod
    def exhale_operation():
        pump_operation(False)
        valve_operation(False)

    @staticmethod
    def exhale_end_operation():
        pump_operation(False)
        valve_operation(False)

    @staticmethod
    def call_mode_exhale():
        subprocess.Popen([sys.executable, './scripts/mode3.py'], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)


class SaveScreen(Screen):
    loading_loop = None
    value = 0
    is_saving = None
    title_text = save_load_title_text
    message_text = save_load_message_text

    flushing_time = config_flush_time
    saving_time = 3

    loading_animation_duration = .5

    # Animations to show and hide various elements over the course of 0.5 second
    animation_show = Animation(opacity=1, duration=.5)
    animation_hide = Animation(opacity=0, duration=.5)

    # Animations for title label to move up and down over the course of 0.5 second
    animation_label_up = Animation(pos_hint={'center_x': .5, 'center_y': .8}, duration=.5)
    animation_label_down = Animation(pos_hint={'center_x': .5, 'center_y': .75}, duration=.5)

    # Animations for loading bar to move up over the course of 0.5 second
    animation_start_bot_line = Animation(points=(100, 150, 700, 150), duration=loading_animation_duration)
    animation_start_top_line = Animation(points=(100, 190, 700, 190), duration=loading_animation_duration)
    animation_start_left_line = Animation(points=(100, 150, 100, 190), duration=loading_animation_duration)
    animation_start_right_line = Animation(points=(700, 150, 700, 190), duration=loading_animation_duration)
    animation_start_load_bar = Animation(pos=[100, 150], duration=loading_animation_duration)

    # Animations for loading bar to move down over the course of 0.5 second
    animation_finish_bot_line = Animation(points=(100, 140, 700, 140), duration=loading_animation_duration)
    animation_finish_top_line = Animation(points=(100, 180, 700, 180), duration=loading_animation_duration)
    animation_finish_left_line = Animation(points=(100, 140, 100, 180), duration=loading_animation_duration)
    animation_finish_right_line = Animation(points=(700, 140, 700, 180), duration=loading_animation_duration)
    animation_finish_load_bar = Animation(pos=[100, 140], duration=loading_animation_duration)

    # Here you can find pump and valve operation method call
    def init(self):
        self.is_saving = False

        # Animation of the appearance of all the screen's elements
        self.move_loading_bar(False)
        self.animate_title_label_show_hide(True)
        self.animate_message_label_show_hide(True)
        self.animate_loading_bar_show_hide(True)
        Clock.schedule_once(lambda l: self.animate_loading_label_show_hide(True), 1)

        # Beginning of the loading animation
        Clock.schedule_once(lambda l: self.start_stop_loading(self.flushing_time, True), 2)
        Clock.schedule_once(lambda l: self.flush_operation(), 2)

    def reset_load_bar(self):
        self.ids.save_loading_bar.set_value(self.value)
        if self.is_saving:
            self.ids.save_loading_label.text = save_load_saving + str(self.value) + '%'
        else:
            self.ids.save_loading_label.text = save_load_flushing + str(self.value) + '%'

    def start_stop_loading(self, time_value, start):
        if start:
            self.loading_loop = Clock.schedule_interval(lambda l: self.animate_loading(), time_value / 100.0)
        else:
            if self.loading_loop is not None:
                self.loading_loop.cancel()
                self.loading_loop = None

    def animate_title_label_show_hide(self, show):
        if show:
            self.animation_show.start(self.ids.save_title_label)
            self.animation_label_down.start(self.ids.save_title_label)
        else:
            self.animation_hide.start(self.ids.save_title_label)
            self.animation_label_up.start(self.ids.save_title_label)

    def animate_message_label_show_hide(self, show):
        if show:
            self.animation_show.start(self.ids.save_message_label)
        else:
            self.animation_hide.start(self.ids.save_message_label)

    def animate_loading_bar_show_hide(self, show):
        if show:
            self.animation_start_bot_line.start(self.ids.save_loading_bar.bot_line)
            self.animation_start_top_line.start(self.ids.save_loading_bar.top_line)
            self.animation_start_left_line.start(self.ids.save_loading_bar.left_line)
            self.animation_start_right_line.start(self.ids.save_loading_bar.right_line)
            self.animation_start_load_bar.start(self.ids.save_loading_bar.load_bar)
            self.animation_show.start(self.ids.save_loading_bar)
        else:
            self.animation_finish_bot_line.start(self.ids.save_loading_bar.bot_line)
            self.animation_finish_top_line.start(self.ids.save_loading_bar.top_line)
            self.animation_finish_left_line.start(self.ids.save_loading_bar.left_line)
            self.animation_finish_right_line.start(self.ids.save_loading_bar.right_line)
            self.animation_finish_load_bar.start(self.ids.save_loading_bar.load_bar)
            self.animation_hide.start(self.ids.save_loading_bar)

    def animate_loading_label_show_hide(self, show):
        if show:
            self.animation_show.start(self.ids.save_loading_label)
        else:
            self.animation_hide.start(self.ids.save_loading_label)

    # Here you can find pump and valve operation method call
    def animate_loading(self):
        if self.value < 101:
            self.ids.save_loading_bar.set_value(self.value)
            self.value += 1
            if self.is_saving:
                self.ids.save_loading_label.text = save_load_saving + str(self.value) + '%'
            else:
                self.ids.save_loading_label.text = save_load_flushing + str(self.value) + '%'
        else:
            if self.is_saving:
                self.ids.save_loading_label.text = save_load_saving + loading_load_text_finish_text
            else:
                self.ids.save_loading_label.text = save_load_flushing + loading_load_text_finish_text
            self.start_stop_loading(0, False)
            self.value = 0
            Clock.schedule_once(lambda l: self.animate_loading_bar_show_hide(False), 1)
            Clock.schedule_once(lambda l: self.animate_loading_label_show_hide(False), .5)
            Clock.schedule_once(lambda l: self.reset_load_bar(), 1.5)
            if self.is_saving:
                self.is_saving = False
                Clock.schedule_once(lambda l: self.animate_title_label_show_hide(False), 2)
                Clock.schedule_once(lambda l: self.animate_message_label_show_hide(False), 2)
                Clock.schedule_once(lambda l: self.start_over(), 3)
            # This is the end of flushing loading, bar is currently invisible so there is an animation to show it as well as call of a method to start saving data and animation for it
            else:
                Clock.schedule_once(lambda l: self.animate_loading_bar_show_hide(True), 2)
                Clock.schedule_once(lambda l: self.animate_loading_label_show_hide(True), 2.5)
                Clock.schedule_once(lambda l: self.start_stop_loading(self.saving_time, True), 4)
                Clock.schedule_once(lambda l: self.save_data_operation(), 4)
                self.is_saving = True

    def move_loading_bar(self, up):
        if up:
            self.ids.save_loading_bar.bot_line.points = (100, 150, 700, 150)
            self.ids.save_loading_bar.top_line.points = (100, 190, 700, 190)
            self.ids.save_loading_bar.left_line.points = (100, 150, 100, 190)
            self.ids.save_loading_bar.right_line.points = (700, 150, 700, 190)
            self.ids.save_loading_bar.load_bar.pos = [100, 150]
        else:
            self.ids.save_loading_bar.bot_line.points = (100, 140, 700, 140)
            self.ids.save_loading_bar.top_line.points = (100, 180, 700, 180)
            self.ids.save_loading_bar.left_line.points = (100, 140, 100, 180)
            self.ids.save_loading_bar.right_line.points = (700, 140, 700, 180)
            self.ids.save_loading_bar.load_bar.pos = [100, 140]

    @staticmethod
    def start_over():
        sm.current = 'login_screen'
        Clock.schedule_once(lambda l: login_screen.start_end_login_animation(True), .5)

    def reset_screen(self):
        self.reset_load_bar()

    @staticmethod
    def flush_operation():
        pump_operation(True)
        valve_operation(False)

    @staticmethod
    def save_data_operation():
        pump_operation(False)
        valve_operation(False)

    def csv_save(self):
        pass


class MainBackground(FloatLayout):
    background_image = ObjectProperty(Image(source=main_background))


background_screen = MainBackground()
sm = ScreenManager()

lock_screen = LockScreen(name='lock_screen')
sm.add_widget(lock_screen)
lock_line_top = LockLineTop()
lock_line_bot = LockLineBot()
image_left = ImageLeft()
image_right = ImageRight()
lock_line_bot.opacity = 0
lock_line_top.opacity = 0
lock_screen.add_widget(lock_line_bot, 2)
lock_screen.add_widget(lock_line_top, 2)
lock_screen.add_widget(image_left, 3)
lock_screen.add_widget(image_right, 3)

login_screen = LoginScreen(name='login_screen')
sm.add_widget(login_screen)

loading_screen = LoadingScreen(name='loading_screen')
sm.add_widget(loading_screen)

main_screen = ScreenMain(name='main_screen')
sm.add_widget(main_screen)

save_screen = SaveScreen(name='save_screen')
sm.add_widget(save_screen)

sm.current = 'lock_screen'
# sm.current = 'login_screen'
# login_screen.start_end_login_animation(True)
# sm.current = 'loading_screen'
# loading_screen.init('Ademir')
# sm.current = 'main_screen'
# main_screen.init()
# sm.current = 'save_screen'
# Clock.schedule_once(lambda l: save_screen.init(), 2)
background_screen.add_widget(sm)
init_gpio()


class MyApp(App):
    def build(self):
        return background_screen


MyApp().run()
