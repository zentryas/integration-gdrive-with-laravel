## Integration Google Drive With Laravel

Create Cradential
- Akses https://console.cloud.google.com/
- Select a project -> New Project
- Set enable library "Google Drive API"
- Create OAuth consent screen -> Publish Production
- Create Cradential -> OAuth Client ID
- Add authorized redirect URIs https://developers.google.com/oauthplayground/
- Visit link https://developers.google.com/oauthplayground/
- Klik icon setting -> check use your own OAuth cradentials
- Input Client ID and Client Secret
- In Step 1 check to Drive API v3 (https://www.googleapis.com/auth/drive)
- Klik Authorize APIs
- Confirmation Email
- Get Refresh Token
- Get Folder ID (Link last url in google drive)