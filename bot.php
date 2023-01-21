 <?php
if (!isset($_REQUEST)) {
return;
}

$confirmation_token = '';

$token = '';

$data = json_decode(file_get_contents('php://input'));

switch ($data->type) {
case 'confirmation':
echo $confirmation_token;
break;

case 'wall_post_new':
$object = $data->object;
$post_id = $object->id;
$text = $object->text;
$date = $object->date;
$norm = date("d.m.Y", $date + 86400);
$owner_id = $object->owner_id;
$from_id = $object->from_id;
$post_type = $object->post_type;
$user_id = $object->from_id;
$user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$from_id}&v=5.0"));
$user_name = $user_info->response[0]->first_name;
if ($post_type == "suggest") {
    $request_params = array(
    'message' => "🔔Новое уведомление🔔\n\nПредложена новая запись, посмотри что там\n\n⚠Обратить внимание⚠\n@id(Ася): проверь и отредактируй пост\n@id(Аля): не забудь прикрепить контент к посту\n@id(Ксюша): проверь всё и опубликуй до {$norm}\nВсе остальные: ваши предложения и правки.\n\nПост от: vk.com/id{$from_id}\nСсылка на пост: https://vk.com/wall{$owner_id}_{$post_id}\n\nТекст поста:\n{$text}",
    'chat_id' => '6',
    'access_token' => $token,
    'v' => '5.103',
    'random_id' => '0'
    );
    $get_params = http_build_query($request_params);
    file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
}
echo('ok');
break; 

}
?>
