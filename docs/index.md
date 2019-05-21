# Welcome to the Docs

This docs are here to explain the usage of the WAVETROPHY App
The official website is [wavetrophy.com](https://wavetrophy.com).
All source code is open source an can be found on [GitHub](https://github.com/wavetrophy). 
The corresponding mobile application can also be found there.

## Usage

The WAVETROPHY App is developed to assist a tour manager with a WAVETROPHY and replace the 
old PDF roadbook.

The structure of the data is like shown below

```
| WAVETROPHY
└──_ Groups
   └──_ Teams
      ├ ─ Users
      ├ ─ Hotel
      └ ─ Event
```

This structure in words:

 - A WAVETROPHY is the root of everything.
 - A Group is always connected to a WAVETROPHY
 - A Team is always connected to a Group
 - A User might be connected to a team
 - A Hotel might be connected to a team (also possible to connect to a user, but only on request to [technical contact](#technical-contact))
 - An Event might be connected to a team
 
To insert the data properly, it is recommended to insert the records in the following order

 1. Create a [WAVE](./wave/index.md#create)
 2. Create all [Users](./user/index.md#create)
 3. Create all [Groups](./group/index.md#create)
 4. Create all [Teams](./team/index.md#create)
 5. Create all [Events](./event/index.md#create)
 6. Create all [Hotels](./hotel/index.md#create)
 
## Contacts

### Owner

Louis Palmer
[louis@wavetrophy.com](mailto:louis@wavetrophy.com)

### Technical Contact 

Björn Pfoster
[contact@darker.dev](mailto:contact@darker.dev)
