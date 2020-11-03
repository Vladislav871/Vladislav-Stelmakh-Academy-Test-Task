<?php

function intervalConstruction($arr) {
    // Проверка на количество аргументов, переданных в массиве

    if(count($arr) > 3) return "Illegal number of elements in input array";
    if(strstr($arr[1], 'bb') || strstr($arr[1], '##')) return "Cannot identify the note";
    if($arr[2] == null) $arr[2] = 'asc';

    // Допустимые значения интервалов

    $allowIntervals = array(['m2', 1, 2], ['M2', 2, 2], ['m3', 3, 3], ['M3', 4, 3],
        ['P4', 5, 4], ['P5', 7, 5], ['m6', 8, 6], ['M6', 9, 6],
        ['m7', 10, 7], ['M7', 11, 7], ['P8', 12, 8]);

    // Допустимые значения нот

    $notes = array(['C', 2], ['D', 2], ['E', 1], ['F', 2], ['G', 2], ['A', 2], ['B', 1]);

    // Извлечение из массива аргументов значений в переменные

    $interval = $arr[0];
    $firstNote = $arr[1];
    $mode = ($arr[2] == 'dsc') ? 'dsc' : 'asc';

    $degree = null;
    $semitones = null;

    // Поиск нужного интервала и извлечение из него кол-во тонов до следующей ноты

    foreach ($allowIntervals as $key => $value) {
        if($interval == $value[0]) {
            $semitones = $value[1];
            $degree = $value[2];
            break;
        }
    }

    // Проверка на инициализацию переменной semitones

    if($semitones == null) return "Cannot identify the interval";

    if($mode == 'asc') {

        $foundNote = null;
        $countSemitone = 0;
        $cycle = false;
        $check = true;

        $indexOfNote = findNoteIndex($firstNote);
        $indexOfNote += ($degree-1);
        if($indexOfNote >= count($notes)) {
            $indexOfNote -= count($notes);
            $foundNote = $notes[$indexOfNote][0];
        } else {
            $foundNote = $notes[$indexOfNote][0];
        }

        $indexForSem = findNoteIndex($firstNote);
        $indexFoundNote = findNoteIndex($foundNote);

        for($i = $indexForSem; $i <= count($notes); $i++) {
            if(strstr($firstNote, '#') && $check) {
                $countSemitone++;
                $check = false;
                continue;
            }
            if(strstr($firstNote, 'b') && $check) {
                $countSemitone++;
                $check = false;
            }
            if($i == count($notes)) {
                for($i = 0; $i <= count($notes); $i++) {
                    if($i == $indexFoundNote) break;
                    else $countSemitone += $notes[$i][1];
                }
                $cycle = true;
            }
            if($cycle) break;
            if($i == $indexFoundNote && $interval != 'P8') break;
            else $countSemitone += $notes[$i][1];
        }

        if($countSemitone == $semitones) {
            return $foundNote;
        } else if($countSemitone > $semitones) {
            if(($countSemitone - $semitones) == 1) {
                $foundNote = $foundNote.'b';
            } else {
                $foundNote = $foundNote.'bb';
            }
        } else {
            if(($semitones - $countSemitone) == 1) {
                $foundNote = $foundNote.'#';
            } else {
                $foundNote = $foundNote.'##';
            }
        }

        return $foundNote;

    } else {
        $foundNote = null;
        $countSemitone = 0;
        $cycle = false;
        $check = true;

        $indexOfNote = findNoteIndex($firstNote);
        $indexOfNote -= ($degree-1);
        if($indexOfNote >= 0 && $indexOfNote <= count($notes)) {
            $foundNote = $notes[$indexOfNote][0];
        } else {
            $indexOfNote += count($notes);
            $foundNote = $notes[$indexOfNote][0];
        }

        $indexForSem = findNoteIndex($firstNote);
        $indexFoundNote = findNoteIndex($foundNote);

        for($i = $indexForSem; $i >= 0; $i--) {
            if(strstr($firstNote, '#') && $check) {
                $countSemitone++;
                $check = false;
                continue;
            }
            if(strstr($firstNote, 'b') && $check) {
                $countSemitone++;
                $check = false;
            }
            if($i == 0) {
                for($i = count($notes); $i >= 0; $i--) {
                    if($i == $indexFoundNote) break;
                    else $countSemitone += $notes[$i-1][1];
                }
                $cycle = true;
            }
            if($cycle) break;
            if($i == $indexFoundNote && $interval != 'P8') break;
            else $countSemitone += $notes[$i-1][1];
        }

        if($countSemitone == $semitones) {
            return $foundNote;
        } else if($countSemitone > $semitones) {
            if(($countSemitone - $semitones) == 1) {
                $foundNote = $foundNote.'#';
            } else {
                $foundNote = $foundNote.'##';
            }
        } else {
            if(($semitones - $countSemitone) == 1) {
                $foundNote = $foundNote.'b';
            } else {
                $foundNote = $foundNote.'bb';
            }
        }

        return $foundNote;
    }

}

function intervalIdentification($arr) {


}

function findNoteIndex($note) {
    $bsharp = array('b', '#');
    $note = str_replace($bsharp, '', $note);
    $notes = array('C', 'D', 'E', 'F', 'G', 'A', 'B');

    foreach($notes as $key => $value) {
        if($note == $value) {
            return $key;
        }
    }
}