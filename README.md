# ledclock
ESP32 ledclock


# 7 LED segements


```
  7
6   1
  5 
4   2
  3
```

## Segments per character
```
+--------+---+---+---+---+---+---+---+--------------------------+
| letter | 1 | 2 | 3 | 4 | 5 | 6 | 7 | STRING                   |
+--------+---+---+---+---+---+---+---+--------------------------+
| A      | X | X |   | X | X | X | X | 1,2,4,5,6,7              |
| a      | X | X | X | X | X |   | X | 1,2,3,4,5,7              |
| B      | X | X | X | X | X | X | X | 1,2,3,4,5,6,7            |
| b      |   | X | X | X | X | X |   | 2,3,4,5,6                |
| C      |   |   | X | X |   | X | X | 3,4,6,7                  |
| c      |   |   | X | X | X |   |   | 3,4,5                    |
| D      | X | X | X | X |   | X | X | 1,2,3,4,6,7              |
| d      | X | X | X | X | X |   |   | 1,2,3,4,5                |
| E      |   |   | X | X | X | X | X | 3,4,5,6,7                |
| e      |   |   | X | X | X | X | X | 3,4,5,6,7                |
| F      |   |   |   | X | X | X | X | 4,5,6,7                  |
| f      |   |   |   | X | X | X | X | 4,5,6,7                  |
| G      |   | X | X | X |   | X | X | 2,3,4,6,7                |
| g      | X | X | X |   | X | X | X | 1,2,3,4,6,7              |
| H      | X | X |   | X | X | X |   | 1,2,4,5,6                |
| h      |   | X |   | X | X | X |   | 2,4,5,6                  |
| I      | X | X |   |   |   |   |   | 1,2                      |
| i      |   | X |   |   |   |   |   | 2                        |
| J      | X | X | X | X |   |   |   | 1,2,3,4                  |
| j      | X | X | X |   |   |   |   | 1,2,3                    |
| K      | X | X |   | X | X | X |   | 1,2,4,5,6,7              |
| k      |   | X |   | X | X | X |   | 2,4,5,6,7                |
| L      |   |   | X | X |   | X |   | 3,4,6                    |
| l      |   |   | X | X |   |   |   | 3,4                      |
| M      |   |   |   |   |   |   |   |                          |
| m      |   |   |   |   |   |   |   |                          |
| N      | X | X |   | X |   | X | X | 1,2,4,6,7                |
| n      |   | X |   | X | X |   |   | 2,4,5                    |
| O      | X | X | X | X |   | X | X | 1,2,3,4,6,7              |
| o      |   | X | X | X | X |   |   | 2,3,4,5                  |
| P      | X |   |   | X | X | X | X | 1,4,5,6,7                |
| p      | X |   |   | X | X | X | X | 1,4,5,6,7                |
| Q      | X | X |   |   | X | X | X | 1,2,5,6,7                |
| q      | X | X |   |   | X | X | X | 1,2,5,6,7                |
| R      | X | X |   | X | X | X | X | 1,2,4,5,6,7              |
| r      |   |   |   | X | X |   |   | 4,5                      |
| S      |   | X | X |   | X | X | X | 2,3,4,6,7                |
| s      |   | X | X |   | X | X | X | 2,3,4,6,7                |
| T      |   |   | X | X | X | X |   | 3,4,5,6                  |
| t      |   |   | X | X | X | X |   | 3,4,5,6                  |
| U      | X | X | X | X |   | X |   | 1,2,3,4,6                |
| u      |   | X | X | X |   |   |   | 2,3,4                    |
| V      | X | X | X | X |   | X |   | 1,2,3,4,6                |
| v      |   | X | X | X |   |   |   | 2,3,4                    |
| W      |   |   |   |   |   |   |   |                          |
| w      |   |   |   |   |   |   |   |                          |
| X      | X | X |   | X | X | X |   | 1,2,4,5,6                |   
| x      | X | X |   | X | X | X |   | 1,2,4,5,6                |
| Y      | X | X |   |   | X | X |   | 1,2,5,6                  |
| y      | X | X |   |   | X | X |   | 1,2,5,6                  |
| Z      | X |   | X | X | X |   | X | 1,3,4,5,7                |
| z      | X |   | X | X | X |   | X | 1,3,4,5,7                |
+--------+---+---+---+---+---+---+---+--------------------------+

+--------+---+---+---+---+---+---+---+--------------------------+
| digit  | 1 | 2 | 3 | 4 | 5 | 6 | 7 | STRINGS                  |
+--------+---+---+---+---+---+---+---+--------------------------+
| 0      | X | X | X | X |   | X | X | 1,2,3,4,6,7              |
| 1      | X | X |   |   |   |   |   | 1,2                      |
| 2      | X |   | X | X | X |   | X | 1,3,4,5,7                |
| 3      | X | X | X |   | X |   | X | 1,2,3,5,7                |
| 4      | X | X |   |   | X | X |   | 1,2,5,6                  |
| 5      |   | X | X |   | X | X | X | 2,3,5,6,7                |
| 6      |   | X | X | X | X | X | X | 2,3,4,5,6,7              |
| 7      | X | X |   |   |   |   | X | 1,2,7                    |
| 8      | X | X | X | X | X | X | X | 1,2,3,4,5,6,7            |
| 9      | X | X | X |   | X | X | X | 1,2,3,5,6,7              |
+--------+---+---+---+---+---+---+---+--------------------------+
```

## LEDS per segment
```
1 =  1 -  9
2 = 10 - 18
3 = 19 - 27
4 = 28 - 36
5 = 37 - 45
6 = 46 - 54
7 = 55 - 63
```