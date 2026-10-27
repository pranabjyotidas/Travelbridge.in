TRAVEL BRIDGE — HTML + PHP PACKAGE

REQUIREMENTS
- Shared hosting with PHP 8.0 or newer.
- PHP mail() configured by the hosting provider.
- travelbridge.in email/DNS configured so hello@travelbridge.in can send mail.

UPLOAD
Upload index.html, styles.css, app.js, register.php, and the complete assets
and storage folders into the same public directory.

REGISTRATIONS
- Valid submissions are appended to storage/registrations.csv.
- The included storage/.htaccess blocks public web access on Apache hosting.
- A company notification is sent to sales@travelbridge.in.
- A branded welcome message is sent to the customer.

IMPORTANT
Some hosts disable PHP mail() or require SMTP authentication. In that case the
registration is still stored in registrations.csv, but email requires your
host's SMTP settings or the Resend integration.

For Nginx hosting, configure the server to deny access to /storage/ because
.htaccess applies only to Apache-compatible hosting.
