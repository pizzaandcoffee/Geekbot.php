<?php

/*
 *   This file is part of Geekbot.
 *
 *   Geekbot is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Geekbot is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Geekbot.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Geekbot\Commands;

use Geekbot\Utils;

class fourchan implements messageCommand{
    public static function getName() {
        return "!4chan";
    }

    public function getDescription() {
        return "get a random image from 4chan or a specified board (warning: high amount of shitposting!)";
    }

    public function getHelp() {
        return $this->getDescription() . "
            Usage:
            !4chan ([board])";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        $curloptions = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n" .
                    "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
            )
        );
        $curlcontext = stream_context_create($curloptions);
        $boards = '[a / b / c / d / e / f / g / gif / h / hr / k / m / o / p / r / s / t / u / v / vg / vr / w / wg] [i / ic] [r9k] [s4s] [cm / hm / lgbt / y] [3 / aco / adv / an / asp / biz / cgl / ck / co / diy / fa / fit / gd / hc / his / int / jp / lit / mlp / mu / n / news / out / po / pol / qst / sci / soc / sp / tg / toy / trv / tv / vp / wsg / wsr / x]';
        $boardstrim = str_replace(array('[', ']', '/'), '', $boards);
        $boardspreg = preg_replace('/\s+/', ' ', $boardstrim);
        $boardsarray = explode(' ', $boardspreg);
        if (!isset($messageArray[1])) {
            $theboard = $boardsarray[array_rand($boardsarray)];
        } else {
            $theboard = $messageArray[1];
        }
        if (in_array($theboard, $boardsarray)) {
            $board = $theboard;
            $catalogjson = file_get_contents("https://a.4cdn.org/{$board}/catalog.json", false, $curlcontext);
            $catalog = json_decode($catalogjson);
            $randompage = $catalog[array_rand($catalog)];
            $threadsArray = $randompage->threads;
            $randomthread = $threadsArray[array_rand($threadsArray)];
            $number = $randomthread->no;
            $getthread = file_get_contents("https://a.4cdn.org/{$board}/thread/{$number}.json", false, $curlcontext);
            $thread = json_decode($getthread);
            $hasimage = 0;
            $postnumbers = count($thread->posts);
            $image = null;
            $triednr = [];
            $i = 0;
            $originalthread = "https://boards.4chan.org/{$board}/thread/{$number}";
            while ($hasimage == 0) {
                $postnr = random_int(0, $postnumbers);
                if (in_array($postnr, $triednr)) {
                    if (count($triednr) == $postnumbers) {
                        $hasimage = 2;
                    }
                } else {
                    if (isset($thread->posts[$postnr]->tim)) {
                        $image = $thread->posts[$postnr]->tim . $thread->posts[$postnr]->ext;
                        $hasimage = 1;
                    } else {
                        $triednr[] = $postnr;
                    }
                }
                $i++;
            }
            print("{$i}\n");
            if ($hasimage == 1) {
                $file = "http://i.4cdn.org/{$board}/{$image}";
                $message->reply($file . ' from ' . $originalthread);
            } else {
                $message->reply('there are no images in this thread!');
            }
        } elseif (Utils::isHelp($messageArray)) {
            $message->channel->sendMessage($this->getHelp());
        } else {
            $message->reply("that is not a valid board");
        }
        return $message;
    }

}