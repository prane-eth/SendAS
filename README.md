# SendAS
This contains the Cybersecurity project which is a part of a Hackathon.

This project won 3rd rank in the Hackathon Tech Eden.

[Devfolio Link](https://devfolio.co/submissions/sendas-d94a)

Features:
- The user uploads a file. It generates a download code along with a download link.
- User shares the code or link with a friend who will download the uploaded file.
- File is encrypted after upload, and decrypted before download.
- File gets expired in 24 hours if not downloaded.
- Detects whether a request if from a real user or bot using HTTP User Agent.
- /uploads/ folder is protected from public by creating index.php which redirects the user to homepage.
- Input is validated to prevent cyber attacks like SQL-injection.
- Files are encrypted using AES-128-CBC algorithm before getting stored in the server.
