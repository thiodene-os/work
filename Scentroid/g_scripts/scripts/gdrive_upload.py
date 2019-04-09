from __future__ import print_function
from googleapiclient.discovery import build
from httplib2 import Http
from oauth2client import file, client, tools
from apiclient.http import MediaFileUpload
import os

# If modifying these scopes, delete the file token.json.
SCOPES = 'https://www.googleapis.com/auth/drive'

def main():
    path = '../result_documents/live/'
    filelist = os.listdir(path)

    """Shows basic usage of the Drive v3 API.
    Prints the names and ids of the first 10 files the user has access to.
    """
    store = file.Storage('token.json')
    creds = store.get()
    if not creds or creds.invalid:
        flow = client.flow_from_clientsecrets('credentials.json', SCOPES)
        creds = tools.run_flow(flow, store)
    service = build('drive', 'v3', http=creds.authorize(Http()))

    # Call the Drive v3 API
    results = service.files().list(
        pageSize=10, fields="nextPageToken, files(id, name)").execute()
    items = results.get('files', [])

    if not items:
        print('No files found.')
    else:
        for filename in filelist: 
            file_metadata = {'name': filename}
            media = MediaFileUpload(path + filename,
                                    mimetype='text/csv')
            upfile = service.files().create(body=file_metadata,
                                                media_body=media,
                                                fields='id').execute()
            print ('File ID: %s' % upfile.get('id'))

if __name__ == '__main__':
    main()
