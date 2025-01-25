<?php

namespace App\Operator;

class SoundExpression
{

    public static function generate($word): string
    {
        //echo "<br>input: <b>" . $word . "</b>";

        $code = "";
        $word = mb_strtolower($word);

        if (strlen($word) < 1) {
            return "";
        }

        // Umwandlung: v->f, w->f, j->i, y->i, ph->f, ä->a, ö->o, ü->u, ß->ss, é->e, è->e, ê->e, à->a, á->a, â->a, ë->e
        $word = str_replace(array("ç", "v", "w", "j", "y", "ph", "ä", "ö", "ü", "ß", "é", "è", "ê", "à", "á", "â", "ë"), array("c", "f", "f", "i", "i", "f", "a", "o", "u", "ss", "e", "e", "e", "a", "a", "a", "e"), $word);
        //echo "<br>optimiert1: <b>" . $word . "</b>";

        // Nur Buchstaben (keine Zahlen, keine Sonderzeichen)
        $word = preg_replace('/[^a-zA-Z]/', '', $word);
        //echo "<br>optimiert2: <b>" . $word . "</b>";


        $wordlen = strlen($word);
        $char = str_split($word);


        // Sonderfälle bei Wortanfang (Anlaut)
        if ($char[0] == 'c') {
            if ($wordlen == 1) {
                $code = 8;
                $x = 1;
            } else {
                // vor a,h,k,l,o,q,r,u,x
                switch ($char[1]) {
                    case 'a':
                    case 'h':
                    case 'k':
                    case 'l':
                    case 'o':
                    case 'q':
                    case 'r':
                    case 'u':
                    case 'x':
                        $code = "4";
                        break;
                    default:
                        $code = "8";
                        break;
                }
                $x = 1;
            }
        } else {
            $x = 0;
        }

        for (; $x < $wordlen; $x++) {

            switch ($char[$x]) {
                case 'a':
                case 'e':
                case 'i':
                case 'o':
                case 'u':
                    $code .= "0";
                    break;
                case 'b':
                case 'p':
                    $code .= "1";
                    break;
                case 'd':
                case 't':
                    if ($x + 1 < $wordlen) {
                        switch ($char[$x + 1]) {
                            case 'c':
                            case 's':
                            case 'z':
                                $code .= "8";
                                break;
                            default:
                                $code .= "2";
                                break;
                        }
                    } else {
                        $code .= "2";
                    }
                    break;
                case 'f':
                    $code .= "3";
                    break;
                case 'g':
                case 'k':
                case 'q':
                    $code .= "4";
                    break;
                case 'c':
                    if ($x + 1 < $wordlen) {
                        switch ($char[$x + 1]) {
                            case 'a':
                            case 'h':
                            case 'k':
                            case 'o':
                            case 'q':
                            case 'u':
                            case 'x':
                                switch ($char[$x - 1]) {
                                    case 's':
                                    case 'z':
                                        $code .= "8";
                                        break;
                                    default:
                                        $code .= "4";
                                }
                                break;
                            default:
                                $code .= "8";
                                break;
                        }
                    } else {
                        $code .= "4";
                    }
                    break;
                case 'x':
                    if ($x > 0) {
                        switch ($char[$x - 1]) {
                            case 'c':
                            case 'k':
                            case 'q':
                                $code .= "8";
                                break;
                            default:
                                $code .= "48";
                                break;
                        }
                    } else {
                        $code .= "48";
                    }
                    break;
                case 'l':
                    $code .= "5";
                    break;
                case 'm':
                case 'n':
                    $code .= "6";
                    break;
                case 'r':
                    $code .= "7";
                    break;
                case 's':
                case 'z':
                    $code .= "8";
                    break;
            }

        }
        //echo "<br>code1: <b>" . $code . "</b><br />";

        // Mehrfach Codes entfernen
        $code = preg_replace("/(.)\\1+/", "\\1", $code);

        //echo "<br>code2: <b>" . $code . "</b><br />";

        // entfernen aller Codes "0" ausser am Anfang
        $codelen = strlen($code);
        $num = array();
        $num = str_split($code);
        $phoneticcode = $num[0];

        for ($x = 1; $x < $codelen; $x++) {
            if ($num[$x] != "0") {
                $phoneticcode .= $num[$x];
            }
        }

        return $phoneticcode;
    }

}