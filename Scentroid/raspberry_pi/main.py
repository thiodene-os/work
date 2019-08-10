# -*- coding: utf-8 -*-
from kivy.animation import Animation
from kivy.app import App
from kivy.clock import Clock
from kivy.core import window
from kivy.lang import Builder
from kivy.uix.screenmanager import Screen, ScreenManager
# noinspection PyProtectedMember
from kivy.uix.button import ButtonBehavior
from kivy.properties import ObjectProperty
from kivy.uix.popup import Popup
from kivy.uix.floatlayout import FloatLayout
from kivy.uix.image import Image
from os import listdir
import kivy
import random
from datetime import datetime
import pytz
from scripts.main import Script

kivy.require('1.10.0')

# To be remove its just for testing. RPI display exact size
SIZE = 1
FORMAT = (800, 480)
window.Window.size = [SIZE * iSize for iSize in FORMAT]
window.Window.clearcolor = [0.2, 0.2, 0.2, 1]

kv_path = './kivy_resources/'
for kv in listdir(kv_path):
    Builder.load_file(kv_path + kv)

colors = [
    [255 / 255.0, 255 / 255.0, 255 / 255.0, 1],  # 1 White: id = 0
    [255 / 255.0, 0, 0, 1],  # 2 Red: id = 1
    [0, 255 / 255.0, 0, 1],  # 3 Lime (Very Bright Green): id = 2
    [0, 0, 255 / 255.0, 1],  # 4 Blue: id = 3
    [255 / 255.0, 255 / 255.0, 0, 1],  # 5 Yellow: id = 4
    [0, 255 / 255.0, 255 / 255.0, 1],  # 6 Cyan (Light Blue: Goluboi): id = 5
    [255 / 255.0, 0, 255 / 255.0, 1],  # 7 Magenta (Pink): id = 6
    [192 / 255.0, 192 / 255.0, 192 / 255.0, 1],  # 8 Silver (Light Gray): id = 7
    [128 / 255.0, 0 / 255.0, 0 / 255.0, 1],  # 9 Maroon (Darker): id = 8
    [128 / 255.0, 128 / 255.0, 0 / 255.0, 1],  # 10 Olive (Darker): id = 9
    [0 / 255.0, 128 / 255.0, 0 / 255.0, 1],  # 11 Green (Darker): id = 10
    [128 / 255.0, 0 / 255.0, 128 / 255.0, 1],  # 12 Purple: id = 11
    [0 / 255.0, 128 / 255.0, 128 / 255.0, 1],  # 13 Teal (Darker Lighter Blue: Darker Goluboi): id = 12
    [0 / 255.0, 0 / 255.0, 128 / 255.0, 1],  # 14 Navy (Darker Blue): id = 13
    [255 / 255.0, 133 / 255.0, 27 / 255.0, 1],  # 15 Orange: id = 14
    [69 / 255.0, 78 / 255.0, 158 / 255.0, 1],  # 16 Liberty (Different Purple): id = 15
    [80 / 255.0, 21 / 255.0, 55 / 255.0, 1],  # 17 Dark Purple: id = 16
    [184 / 255.0, 196 / 255.0, 128 / 255.0, 1],  # 18 Sage (Gray + Green Mix): id = 17
    [212 / 255.0, 231 / 255.0, 158 / 255.0, 1],  # 19 Pale Golden (Lighter Sage): id = 18
    [41 / 255.0, 21 / 255.0, 40 / 255.0, 1],  # 20 Raisin Black: id = 19
    [57 / 255.0, 79 / 255.0, 73 / 255.0, 1],  # 21 Arsenic (Green + Dark Gray Mix): id = 20
    [101 / 255.0, 116 / 255.0, 58 / 255.0, 1],  # 22 Fern Green (Dying Green): id = 21
    [239 / 255.0, 221 / 255.0, 141 / 255.0, 1],  # 23 Light Khaki (Very Light Brown): id = 22
    [51 / 255.0, 15 / 255.0, 10 / 255.0, 1],  # 24 Zinnwaldite Brown: id = 23
    [172 / 255.0, 203 / 255.0, 225 / 255.0, 1],  # 25 Light Steel Blue: id = 24
    [0 / 255.0, 100 / 255.0, 100 / 255.0, 1],  # 26 Tropical Rain Forest: id = 25
    [169 / 255.0, 63 / 255.0, 85 / 255.0, 1],  # 27 English Red: id = 26
    [25 / 255.0, 50 / 255.0, 60 / 255.0, 1],  # 28 Yankees Blue: id = 27
    [28 / 255.0, 28 / 255.0, 28 / 255.0, 1],  # 29 Gray: id = 28
    [0, 0, 0, 1]  # 30 Black: id = 29
]
datetime_format = '%d/%m/%y %H:%M:%S'
eastern = pytz.timezone('US/Eastern')
region_list = [
    'Africa',
    'America',
    'Antarctica',
    'Arctic',
    'Asia',
    'Atlantic',
    'Australia',
    'Canada',
    'Europe',
    'Indian',
    'Pacific',
    'US'
]
zone_list = [
    ['Abidjan', 'Accra', 'Addis Ababa', 'Algiers', 'Asmara', 'Bamako', 'Bangui', 'Banjul', 'Bissau', 'Blantyre', 'Brazzaville', 'Bujumbura', 'Cairo', 'Casablanca', 'Ceuta', 'Conakry', 'Dakar', 'Dar es Salaam', 'Djibouti', 'Douala', 'El Aaiun', 'Freetown', 'Gaborone', 'Harare', 'Johannesburg', 'Juba', 'Kampala', 'Khartoum', 'Kigali', 'Kinshasa', 'Lagos', 'Libreville', 'Lome', 'Luanda', 'Lubumbashi', 'Lusaka', 'Malabo', 'Maputo', 'Maseru', 'Mbabane', 'Mogadishu', 'Monrovia', 'Nairobi', 'Ndjamena', 'Niamey', 'Nouakchott', 'Ouagadougou', 'Porto-Novo', 'Sao Tome', 'Tripoli', 'Tunis', 'Windhoek'],
    ['Adak', 'Anchorage', 'Anguilla', 'Antigua', 'Araguaina', 'Argentina/Buenos Aires', 'Argentina/Catamarca', 'Argentina/Cordoba', 'Argentina/Jujuy', 'Argentina/La Rioja', 'Argentina/Mendoza', 'Argentina/Rio Gallegos', 'Argentina/Salta', 'Argentina/San Juan', 'Argentina/San Luis', 'Argentina/Tucuman', 'Argentina/Ushuaia', 'Aruba', 'Asuncion', 'Atikokan', 'Bahia', 'Bahia Banderas', 'Barbados', 'Belem', 'Belize', 'Blanc-Sablon', 'Boa Vista', 'Bogota', 'Boise', 'Cambridge Bay', 'Campo Grande', 'Cancun', 'Caracas', 'Cayenne', 'Cayman', 'Chicago', 'Chihuahua', 'Costa Rica', 'Creston', 'Cuiaba', 'Curacao', 'Danmarkshavn', 'Dawson', 'Dawson Creek', 'Denver', 'Detroit', 'Dominica', 'Edmonton', 'Eirunepe', 'El Salvador', 'Fort Nelson', 'Fortaleza', 'Glace Bay', 'Godthab', 'Goose Bay', 'Grand Turk', 'Grenada', 'Guadeloupe', 'Guatemala', 'Guayaquil', 'Guyana', 'Halifax', 'Havana', 'Hermosillo', 'Indiana/Indianapolis', 'Indiana/Knox', 'Indiana/Marengo', 'Indiana/Petersburg', 'Indiana/Tell City', 'Indiana/Vevay', 'Indiana/Vincennes', 'Indiana/Winamac', 'Inuvik', 'Iqaluit', 'Jamaica', 'Juneau', 'Kentucky/Louisville', 'Kentucky/Monticello', 'Kralendijk', 'La Paz', 'Lima', 'Los Angeles', 'Lower Princes', 'Maceio', 'Managua', 'Manaus', 'Marigot', 'Martinique', 'Matamoros', 'Mazatlan', 'Menominee', 'Merida', 'Metlakatla', 'Mexico City', 'Miquelon', 'Moncton', 'Monterrey', 'Montevideo', 'Montserrat', 'Nassau', 'New York', 'Nipigon', 'Nome', 'Noronha', 'North Dakota/Beulah', 'North Dakota/Center', 'North Dakota/New Salem', 'Ojinaga', 'Panama', 'Pangnirtung', 'Paramaribo', 'Phoenix', 'Port-au-Prince', 'Port of Spain', 'Porto Velho', 'Puerto Rico', 'Punta Arenas', 'Rainy River', 'Rankin Inlet', 'Recife', 'Regina', 'Resolute', 'Rio Branco', 'Santarem', 'Santiago', 'Santo Domingo', 'Sao Paulo', 'Scoresbysund', 'Sitka', 'St Barthelemy', 'St Johns', 'St Kitts', 'St Lucia', 'St Thomas', 'St Vincent', 'Swift Current', 'Tegucigalpa', 'Thule', 'Thunder Bay', 'Tijuana', 'Toronto', 'Tortola', 'Vancouver', 'Whitehorse', 'Winnipeg', 'Yakutat', 'Yellowknife'],
    ['Casey', 'Davis', 'DumontDUrville', 'Macquarie', 'Mawson', 'McMurdo', 'Palmer', 'Rothera', 'Syowa', 'Troll', 'Vostok'],
    ['Longyearbyen'],
    ['Aden', 'Almaty', 'Amman', 'Anadyr', 'Aqtau', 'Aqtobe', 'Ashgabat', 'Atyrau', 'Baghdad', 'Bahrain', 'Baku', 'Bangkok', 'Barnaul', 'Beirut', 'Bishkek', 'Brunei', 'Chita', 'Choibalsan', 'Colombo', 'Damascus', 'Dhaka', 'Dili', 'Dubai', 'Dushanbe', 'Famagusta', 'Gaza', 'Hebron', 'Ho Chi Minh', 'Hong Kong', 'Hovd', 'Irkutsk', 'Jakarta', 'Jayapura', 'Jerusalem', 'Kabul', 'Kamchatka', 'Karachi', 'Kathmandu', 'Khandyga', 'Kolkata', 'Krasnoyarsk', 'Kuala Lumpur', 'Kuching', 'Kuwait', 'Macau', 'Magadan', 'Makassar', 'Manila', 'Muscat', 'Nicosia', 'Novokuznetsk', 'Novosibirsk', 'Omsk', 'Oral', 'Phnom Penh', 'Pontianak', 'Pyongyang', 'Qatar', 'Qyzylorda', 'Riyadh', 'Sakhalin', 'Samarkand', 'Seoul', 'Shanghai', 'Singapore', 'Srednekolymsk', 'Taipei', 'Tashkent', 'Tbilisi', 'Tehran', 'Thimphu', 'Tokyo', 'Tomsk', 'Ulaanbaatar', 'Urumqi', 'Ust-Nera', 'Vientiane', 'Vladivostok', 'Yakutsk', 'Yangon', 'Yekaterinburg', 'Yerevan'],
    ['Azores', 'Bermuda', 'Canary', 'Cape Verde', 'Faroe', 'Madeira', 'Reykjavik', 'South Georgia', 'St Helena', 'Stanley', 'lantic'],
    ['Adelaide', 'Brisbane', 'Broken Hill', 'Currie', 'Darwin', 'Eucla', 'Hobart', 'Lindeman', 'Lord Howe', 'Melbourne', 'Perth', 'Sydney'],
    ['Central', 'Eastern', 'Mountain', 'Newfoundland', 'Pacific'],
    ['Amsterdam', 'Andorra', 'Astrakhan', 'Athens', 'Belgrade', 'Berlin', 'Bratislava', 'Brussels', 'Bucharest', 'Budapest', 'Busingen', 'Chisinau', 'Copenhagen', 'Dublin', 'Gibraltar', 'Guernsey', 'Helsinki', 'Isle of Man', 'Istanbul', 'Jersey', 'Kaliningrad', 'Kiev', 'Kirov', 'Lisbon', 'Ljubljana', 'London', 'Luxembourg', 'Madrid', 'Malta', 'Mariehamn', 'Minsk', 'Monaco', 'Moscow', 'Oslo', 'Paris', 'Podgorica', 'Prague', 'Riga', 'Rome', 'Samara', 'San Marino', 'Sarajevo', 'Saratov', 'Simferopol', 'Skopje', 'Sofia', 'Stockholm', 'Tallinn', 'Tirane', 'Ulyanovsk', 'Uzhgorod', 'Vaduz', 'Vatican', 'Vienna', 'Vilnius', 'Volgograd', 'Warsaw', 'Zagreb', 'Zaporozhye', 'Zurich'],
    ['Antananarivo', 'Chagos', 'Christmas', 'Cocos', 'Comoro', 'Kerguelen', 'Mahe', 'Maldives', 'Mauritius', 'Mayotte', 'Reunion'],
    ['Apia', 'Auckland', 'Bougainville', 'Chatham', 'Chuuk', 'Easter', 'Efate', 'Enderbury', 'Fakaofo', 'Fiji', 'Funafuti', 'Galapagos', 'Gambier', 'Guadalcanal', 'Guam', 'Honolulu', 'Kiritimati', 'Kosrae', 'Kwajalein', 'Majuro', 'Marquesas', 'Midway', 'Nauru', 'Niue', 'Norfolk', 'Noumea', 'Pago Pago', 'Palau', 'Pitcairn', 'Pohnpei', 'Port Moresby', 'Rarotonga', 'Saipan', 'Tahiti', 'Tarawa', 'Tongatapu', 'Wake', 'Wallis', 'ic'],
    ['Alaska', 'Arizona', 'Central', 'Eastern', 'Hawaii', 'Mountain']
]

is_idle = False
idle_count = 0
idle_clock = None


# noinspection PyUnusedLocal,SpellCheckingInspection
def on_motion(self, etype, motionevent):
    global is_idle, idle_count
    is_idle = True


window.Window.bind(on_motion=on_motion)


# Auto lock method
def check_idle():
    global idle_count, is_idle
    if is_idle is True:
        is_idle = False
        idle_count = 0
    else:
        if idle_count >= 30:
            is_idle = False
            idle_count = 0
        else:
            idle_count += 1


def convert_timestamp(input_timestamp):
    try:
        timestamp = float(input_timestamp)
        timestamp = eastern.localize(datetime.fromtimestamp(timestamp))
    except ValueError:
        timestamp = eastern.localize(datetime.strptime(input_timestamp, datetime_format))
    timezone_temp = pytz.timezone('Canada/Eastern')
    result = timestamp.astimezone(timezone_temp)
    result = result.strftime(datetime_format)
    return result


class MainScreen(FloatLayout):
    background_image = ObjectProperty(Image(source='./images/background.png'))
    errors = None
    i = 0

    def test(self):
        self.i += 1
        print self.i

    Clock.schedule_interval(lambda a: ms.test(), .0001)


class LockButton(ButtonBehavior, Image):
    def lock(self):
        Clock.schedule_once(lambda a: self.go_welcome(), .5)
        Clock.schedule_once(lambda a: welcome_screen.lock(), .5)
        Clock.schedule_once(lambda a: ms.hide_lock_button(), .5)

    @staticmethod
    def go_welcome():
        sm.transition.direction = 'up'
        sm.current = 'welcome'

    def lock_buttons(self):
        self.disabled = True

    def unlock_buttons(self):
        self.disabled = False


class UnlockButton(ButtonBehavior, Image):
    def login(self):
        welcome_screen.login(self)


class TestScreen(Screen):
    def test(self):
        script.start()
        Clock.schedule_once(lambda a: self.update_label(), 3)

    def update_label(self):
        self.ids.test_label = script.get_output()


class WelcomeScreen(Screen):

    def lock_buttons(self):
        self.ids.welcome_unlock_button.disabled = True
        ms.ids.lock_button.disabled = True

    def unlock_buttons(self):
        self.ids.welcome_unlock_button.disabled = False
        ms.ids.lock_button.disabled = False

    def lock(self):
        self.lock_animation()
        self.ids.welcome_unlock_button.opacity = 1

    def lock_animation(self):
        self.lock_buttons()
        animation = Animation(opacity=1, d=1.5)
        animation.start(self.ids.welcome_label)
        animation.start(self.ids.welcome_unlock_button)
        Clock.schedule_once(lambda a: self.unlock_buttons(), 1)

    def login(self, instance):
        animation = Animation(opacity=0, d=0.3)
        animation.start(self.ids.welcome_label)
        animation.start(instance)
        animation.start(self.ids.welcome_unlock_button)
        # Clock.schedule_once(lambda dt: self.animation(), 1)
        Clock.schedule_once(lambda a: self.log_into(), .3)
        self.unlock_buttons()

    # Place holder (replace with 2 other methods below
    @staticmethod
    def log_into():
        sm.transition.direction = 'down'
        sm.current = 'intro'
        # intro_screen.init()


class IntroScreen(Screen):

    def init(self):
        Clock.schedule_once(lambda a: self.animate_label1(), 1)

    def animate_label1(self):
        label_animation = Animation(opacity=1, d=1)
        label_animation.start(self.ids.intro_label1)
        Clock.schedule_once(lambda a: self.animate_label2(), 1.01)

    def animate_label2(self):
        label2_animation = Animation(opacity=1, d=1)
        label2_animation.start(self.ids.intro_label2)
        Clock.schedule_once(lambda a: self.animate_pb(), 1.01)

    def animate_label3(self):
        label3_animation = Animation(opacity=1, d=1)
        label3_animation.start(self.ids.intro_label3)
        Clock.schedule_once(lambda a: self.unlock_animation(), 2.01)

    def animate_pb(self):
        pb_animation = Animation(opacity=1, d=1)
        pb_animation.start(self.ids.pb)
        pb_animation.start(self.ids.pb_label)
        self.ids.pb.value = 0
        Clock.schedule_once(lambda a: self.pb_work(), 1)

    def unlock_animation(self):
        unlock_animation = Animation(opacity=0, d=1)
        unlock_animation.start(self.ids.intro_label1)
        unlock_animation.start(self.ids.intro_label2)
        unlock_animation.start(self.ids.intro_label3)
        unlock_animation.start(self.ids.pb_label)
        unlock_animation.start(self.ids.pb)
        Clock.schedule_once(lambda a: self.end(), 1.5)

    def pb_work(self):
        ran_time = float(random.randint(3, 6))
        ran_point = ran_time / 100
        event = Clock.schedule_interval(lambda a: self.pb_fill_1(), ran_point)
        Clock.schedule_once(lambda a: event.cancel(), ran_time + 1)
        Clock.schedule_once(lambda a: self.animate_label3(), ran_time + .7)

    def pb_fill_1(self):
        self.ids.pb.value = self.ids.pb.value + 1

    @staticmethod
    def end():
        sm.current = 'welcome'


class CustomPopup(Popup):
    pass


class RootWidget(FloatLayout):
    pass


ms = MainScreen()
sm = ScreenManager()
script = Script()

test_screen = TestScreen(name='test')
sm.add_widget(test_screen)

# welcome_screen = WelcomeScreen(name='welcome')
# sm.add_widget(welcome_screen)
#
# intro_screen = IntroScreen(name='intro')
# sm.add_widget(intro_screen)

sm.current = 'test'
ms.add_widget(sm)


class MyApp(App):
    def build(self):
        return ms


MyApp().run()
