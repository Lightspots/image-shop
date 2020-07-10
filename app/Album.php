<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Album extends Model
{
    protected $fillable = ['key', 'path', 'public', 'name'];

    public function order() {
        $this->hasMany('App\Order');
    }

    public static function generateKey() {
        $length = intval(env("SHOP_ALBUM_KEY_LENGTH"));
        if ($length < 4) {
          return Uuid::uuid4()->toString();
        } else {
          return self::randomString($length);
        }
    }

    private static function randomString($length) {
      // The characters we want in the output
      $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
      $count = strlen($chars);

      // Generate random bytes
      $bytes = random_bytes($length);

      // Construct the output string
      $result = '';
      // Split the string of random bytes into individual characters
      foreach (str_split($bytes) as $byte) {
        // ord($byte) converts the character into an integer between 0 and 255
        // ord($byte) % $count wrap it around $chars
        $result .= $chars[ord($byte) % $count];
      }
      return $result;
    }
}
