<?php
session_start();
require_once('constants.php');

if (!isset($_SESSION['access_token'])) {
    header("Location: index.php");
    exit();
}

$access_token = $_SESSION['access_token'];

function getTwitchUser($access_token) {
    $url = "https://api.twitch.tv/helix/users";
    $headers = [
        "Authorization: Bearer $access_token",
        "Client-Id: d87eeiwk7h71fryewxo0x09gv5qak9"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$user_data = getTwitchUser($access_token);
$user_id = $user_data['data'][0]['id'];
$user_name = $user_data['data'][0]['display_name'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
    <div id="user-list">
        <h2>Liste of users with "!play"</h2>
        <ul id="user-list-items"></ul>
    </div>

    <script>
        $(document).ready(function() {
            const channel = '<?php echo CHANNEL; ?>';
            const accessToken = '<?php echo $access_token; ?>';
            const clientId = '<?php echo CLIENT_ID ?>';

            const socket = new WebSocket('wss://irc-ws.chat.twitch.tv:443');

            socket.onopen = function() {
                socket.send('PASS oauth:' + accessToken);
                socket.send('NICK justinfan' + Math.floor(Math.random() * 10000));
                socket.send('JOIN #' + channel);
            };

            socket.onmessage = function(event) {
                const message = event.data;
                if (message.includes('PRIVMSG')) {
                    const parts = message.split(' ');
                    const user = parts[0].split('!')[0].substring(1);
                    const text = parts.slice(3).join(' ').substring(1);

                    if (text.trim() === '!play') {
                        $.ajax({
                            url: 'https://api.twitch.tv/helix/users',
                            headers: {
                                'Authorization': 'Bearer ' + accessToken,
                                'Client-Id': clientId
                            },
                            data: {
                                login: user
                            },
                            success: function(response) {
                                const displayName = response.data[0].display_name;
                                const $userListItems = $('#user-list-items');
                                const $existingItem = $userListItems.find('li:contains(' + displayName + ')');

                                if ($existingItem.length === 0) {
                                    $userListItems.append('<li>' + displayName + '</li>');
                                }
                            }
                        });
                    }
                }
            };
        });
    </script>
</body>
</html>