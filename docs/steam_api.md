# Steam API

## GetAppList API 
URL: https://api.steampowered.com/IStoreService/GetAppList/v1/?key=<api_key>

Response Data:
```
{
    "response": {
        "apps": [
            {
                "appid": 10,
                "name": "Counter-Strike",
                "last_modified": 1666823513,
                "price_change_number": 20837971
            }
            ...
        ]
    }
}
```
## GetMostPopularTags API
URL: https://api.steampowered.com/IStoreService/GetMostPopularTags/v1/?key=<api_key>

Response Data:
```
{
    "response": {
        "tags": [
            {
                "tagid": 492,
                "name": "Indie"
            }
            ...
        ]
    }
}
```

## GetPlayerSummaries API
URL: https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=<api_key>&steamids=<steam_id>

Response Data:
```
{
    "response": {
        "players": [
            {
                "steamid": "76561198093057200",
                "communityvisibilitystate": 3,
                "profilestate": 1,
                "personaname": "Rotato",
                "commentpermission": 1,
                "profileurl": "https://steamcommunity.com/profiles/76561198093057200/",
                "avatar": "https://avatars.steamstatic.com/780475fcf41b33fa7ad7ea83d417e360706869c1.jpg",
                "avatarmedium": "https://avatars.steamstatic.com/780475fcf41b33fa7ad7ea83d417e360706869c1_medium.jpg",
                "avatarfull": "https://avatars.steamstatic.com/780475fcf41b33fa7ad7ea83d417e360706869c1_full.jpg",
                "avatarhash": "780475fcf41b33fa7ad7ea83d417e360706869c1",
                "lastlogoff": 1699875111,
                "personastate": 0,
                "primaryclanid": "103582791433673215",
                "timecreated": 1369954800,
                "personastateflags": 0
            }
            ...
        ]
    }
}
```

## GetOwnedGames API
URL: https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=<api_key>&steamid=<steam_id>

Response Data:
```
{
    "response": {
        "game_count": 186,
        "games": [
            {
                "appid": 4000,
                "playtime_forever": 28893,
                "playtime_windows_forever": 320,
                "playtime_mac_forever": 0,
                "playtime_linux_forever": 0,
                "rtime_last_played": 1636521191,
                "playtime_disconnected": 0
            }
            ...
        ]
    }
}
```