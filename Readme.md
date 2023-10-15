# Web Info monitor for WD EX4100

The NAS Server Western Digital My Cloud EX4100 can be polled by SNMP Protocol, but is too complicated for some users.
This file cames to simplify the process.

The main target of this is to be used with the [Home Assistant platform](https://www.home-assistant.io/)

Some of the code is based on the work:
- Get Monitors info: by [EventuallyFixed](https://community.home-assistant.io/t/western-digital-my-cloud/40610/6). 
- Slugify: by [@lucasmezencio](https://gist.github.com/lucasmezencio/15d23207834a3eade40c5aeec7c1fc5e])
- PHP Parse df: by [@terrypearson](https://gist.github.com/terrypearson/7bb4549505c6818d6753d7f077ac8c7d)
- Bytes format (/human readable/): by [ryeguy](https://stackoverflow.com/a/2510455) 
- endsWith (to replace PHP8's `str_ends_with`): by [Salman A](https://stackoverflow.com/a/10473026)

## Requirements
- PHP 7.3 (the same of WD Nas updated as today)

## How to Use this file
Just put the file `info.php` inside the folder `/var/www/` of your NAS server.

### I don't know how to put the file inside the folder
First, login into the admin area of your NAS, and Go to `Settings > Network`

Then, if SSH if off, enable the SSH Option. Set a propper Password.

Now login into NAS's ssh

`ssh sshd@ip.of.your.device`

go to `/var/www` or any other wanted folder inside it.
just copy or download the file
For download type this:

```wget https://raw.githubusercontent.com/bredecl/wdex4100PHP/main/info.php -O info.php```

point your local network browser to `http://ip.of.your.device/info.php` to view the file result.

Always change the `ip.of.your.device` for the real IP.

## How to use in Home Assistant
open your `configuration.yaml` file with your favorite text/code editor

then add this code
```

```

## Another info
- By default the Temperature is diplayed in [Celcius Degrees](https://community.wd.com/t/change-temp-reading-to-fahrenheit/269929/2).
- Only will return the info of the NAS disk (not the internal disk for OS)
- the memory is always displayed in [kibibyte](https://en.wikipedia.org/wiki/Kibibyte) (the command `free` available in the NAS doesn't allow parameters), so the json will return the kB
- I don't recommend to pull more than two times per minute (the temperatures info took longer than the other process)