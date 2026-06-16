<?php

class SampQuery
{
    private $socket;

    public function connect($ip, $port)
    {
        $this->socket = fsockopen("udp://$ip", $port, $errno, $errstr, 2);

        if (!$this->socket) {
            return false;
        }

        stream_set_timeout($this->socket, 2);
        return true;
    }

    private function send($packet)
    {
        fwrite($this->socket, $packet);
        return fread($this->socket, 2048);
    }

    public function getInfo($ip, $port)
    {
        if (!$this->connect($ip, $port)) {
            return false;
        }

        // handshake SA:MP
        $packet = "SAMP" .
                  chr(strtok($ip, ".") & 0xFF) .
                  chr(strtok(".") & 0xFF) .
                  chr(strtok(".") & 0xFF) .
                  chr(strtok(".") & 0xFF) .
                  chr($port & 0xFF) .
                  chr($port >> 8);

        fwrite($this->socket, $packet);

        $data = fread($this->socket, 2048);

        if (!$data) return false;

        $pos = 10;

        $info = [];

        // online players
        $info['password'] = ord($data[$pos]);
        $pos++;

        $info['players'] = ord($data[$pos]) + ord($data[$pos+1]) * 256;
        $pos += 2;

        $info['maxplayers'] = ord($data[$pos]) + ord($data[$pos+1]) * 256;
        $pos += 2;

        // hostname
        $len = ord($data[$pos]);
        $pos++;

        $info['hostname'] = substr($data, $pos, $len);
        $pos += $len;

        // gamemode
        $len = ord($data[$pos]);
        $pos++;

        $info['gamemode'] = substr($data, $pos, $len);
        $pos += $len;

        // language
        $len = ord($data[$pos]);
        $pos++;

        $info['language'] = substr($data, $pos, $len);

        fclose($this->socket);

        return $info;
    }
}
```

---

# 📊 2. Использование (index.php или widget)

```php id="samp002"
require_once 'samp_query.php';

$query = new SampQuery();

$serverIP = "127.0.0.1";
$serverPort = 7777;

$info = $query->getInfo($serverIP, $serverPort);

if ($info) {
    echo "Онлайн: " . $info['players'] . "/" . $info['maxplayers'];
    echo "<br>Сервер: " . $info['hostname'];
    echo "<br>Режим: " . $info['gamemode'];
} else {
    echo "Сервер недоступен";
}
```

---

# 🏠 3. Красивый блок для главной

Добавь в `index.php`:

```php id="samp003"
<div class="card">
    <h2>📡 SA:MP Server Status</h2>

    <?php if ($info): ?>

```
    <p>🟢 Онлайн: <?= $info['players'] ?>/<?= $info['maxplayers'] ?></p>
    <p>🏷️ <?= htmlspecialchars($info['hostname']) ?></p>
    <p>🎮 <?= htmlspecialchars($info['gamemode']) ?></p>
<?php else: ?>
    <p>🔴 Сервер недоступен</p>
<?php endif; ?>
```

</div>
```