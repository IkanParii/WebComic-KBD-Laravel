<?php

namespace App\Support;

use Illuminate\Http\Request;

class ManualCaptcha
{
    public static function generate(Request $request, string $context): string
    {
        $left = random_int(1, 20);
        $right = random_int(1, 20);
        $operator = random_int(0, 1) === 0 ? '+' : '-';

        if ($operator === '-' && $right > $left) {
            [$left, $right] = [$right, $left];
        }

        $answer = $operator === '+' ? $left + $right : $left - $right;
        $question = "{$left} {$operator} {$right}";

        $request->session()->put("manual_captcha.{$context}.answer", (string) $answer);
        $request->session()->put("manual_captcha.{$context}.question", $question);

        return $question;
    }

    public static function question(Request $request, string $context): string
    {
        $question = $request->session()->get("manual_captcha.{$context}.question");

        if (! $question) {
            return self::generate($request, $context);
        }

        return (string) $question;
    }
}
