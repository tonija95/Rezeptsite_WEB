# Uploads Folder

Used by PHP to store **user-uploaded recipe images**.

### Rules
- Uploads happen only on the *recipe create/edit* page.
- Allowed file types: `.jpg`, `.jpeg`, `.png`
- Each recipe can have one title image.
- This folder must be writable by PHP (`chmod 755` or equivalent on Windows).

> The folder is listed in `.gitignore` to prevent uploading user files to GitHub.
