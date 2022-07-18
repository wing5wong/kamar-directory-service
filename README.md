# KAMAR Directory Service Example

## .env 
Set your Directory Service Username and Password in the `.env` file.  
See `.env.example` for sample entry.  

## Config file
__Do not set username and password values in the config file.__

Set the desired values in /config/kamar.php for:
- serviceName `e.g. "Kamar Directory Service"`
- serviceVersion `e.g. 1.0`
- infoUrl `e.g. "https://www.myschool.co.nz/more-info"`
- privacyStatement  `e.g. "Change this to a valid privacy statement | All your data belongs to us and will be kept in a top secret vault."`
- options `The options to be requested for your directory service`

## Routes
A single route is defined in `app/routes/kamar.php` accepting `POST` requests to `/kamar`.  
The route is handled by `app/Http/Controllers/HandleKamarPost.php`.  
Base functionality as described in the KAMAR example is implemented here, but you are free to adjust to suit your requirements.

## Middleware
A new `kamar` middleware group is created in `app/Http/Kernel.php`.  
The group is the same as the default `web` group, but has the csrf middleware removed.  
You MUST make sure you are authenticating requests to your application.  

## Storage
The example implementation will write json data files at `/storage/app/data`. These files are not publicly accessible by default.
Consider a task to tidy up these files once processed.
