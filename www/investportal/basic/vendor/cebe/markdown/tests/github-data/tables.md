Tables
------

First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell
Content Cell  | Content Cell

| First Header  | Second Header |
| ------------- | ------------- |
| Content Cell  | Content Cell  |
| Content Cell  | Content Cell  |

| Name | Description          |
| ------------- | ----------- |
| Help      | Display the help window.|
| Close     | Closes a window     |

| Name | Description          |
| ------------- | ----------- |
| Help      | **Display the** help window.|
| Close     | _Closes_ a window     |

| Default-Align | Left-Aligned  | Center Aligned  | Right Aligned |
| ------------- | :------------ |:---------------:| -----:|
| 1             | col 3 is      | some wordy text | $1600 |
| 2             | col 2 is      | centered        |   $12 |
| 3             | zebra stripes | are neat        |    $1 |


Simple | Table
------ | -----
1      | 2
3      | 4

| Simple | Table |
| ------ | ----- |
| 1      | 2     |
| 3      | 4     |
| 3      | 4     \|
| 3      | 4    \\|

Check https://github.com/erusev/parsedown/issues/184 for the following:

Foo | Bar | State
------ | ------ | -----
`Code | Pipe` | Broken | Blank
`Escaped Code \| Pipe` | Broken | Blank
Escaped \| Pipe | Broken | Blank
Escaped \\| Pipe | Broken | Blank
Escaped \\ | Pipe | Broken | Blank

| Simple | Table |
| :----- | ----- |
| 3      | 4     |
3      | 4
5

Mixed | Table
------ | -----
| 1      | 2
3      | 4

| Mixed | Table
------ | -----
| 1      | 2
3      | 4

 Mixed | Table
|------ | ----- |
 1      | 2
| 3      | 4 |

some text

| single col |
| -- |  -- |
| 1 |
2
3

| Table | With | Empty | Cells |
| ----- | ---- | ----- | ----- |
|       |      |       |       |
|   a   |      |   b   |       |
|       |  a   |       |   b   |
|   a   |      |       |   b   |
|       |  a   |   b   |       |

   |
-- | --
   |
   
|   |   |
| - | - |
|   |   |

 | Table | Indentation |
 | ----- | ---- |
   | A     | B    |

  | Table | Indentation |
  | ----- | ---- |
   | A     | B    |

 | Table | Indentation |
   | ----- | ---- |
 | A     | B    |

    | Table | Indentation |
   | ----- | ---- |
 | A     | B    |

| Table | Indentation |
    | :----- | ---- |
    | A     | B    |

| Item      | Value |
| --------- | -----:|
| Computer  | $1600 |
| Phone     |   $12 |
| Pipe      |    $1 |
