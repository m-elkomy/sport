<?php


function lang($phrase){
    static $lang = array(
        'CUP'          => 'Cup',
        'PLAYER'       => 'Player',
        'TEAM'         => 'Team',
        'EDIT PROFILE' => 'Edit Profile',
        'SETTING'      => 'Setting',
        'LOGOUT'       => 'Logout',
        'CUP PROJECT'  => 'Cup Project',
        'MEMBERS'      => 'Members',
        'COACH'        => 'Coach',
    );
    return $lang[$phrase];
}