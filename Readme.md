# Web Info monitor for WD EX4100

The NAS Server Western Digital My Cloud EX4100 can be polled by SNMP Protocol, but is too complicated for some users.
This file cames to simplify the process.

The main target of this is to be used with the [Home Assistant platform](https://www.home-assistant.io/)

Some of the code is Based on the work of [EventuallyFixed](https://community.home-assistant.io/t/western-digital-my-cloud/40610/6). 

Also, SLUGIFY is Provided By [@lucasmezencio](https://gist.github.com/lucasmezencio/15d23207834a3eade40c5aeec7c1fc5e])

## How to Use
Just put the file `info.php` inside the folder `/var/www/` of your NAS server.

### I don't know how to put the file inside the folder
First, login into the admin area of your NAS, and Go to `Settings > Network`

Then, if SSH if off, enable the SSH Option. Set a propper Password.

Now login into NAS's ssh

`ssh sshd@ip.of.your.device`

go to `/var/www` or any other wanted folder inside it.
just copy or download the file
For download type this:
`wget `
