<?php
namespace App\Services;
use Goutte\Client;

class ScraperService
{
	public function get_punch_record(){
		$client  = new Client();
		$crawler = $client->request('GET', url('login'));
		$form    = $crawler->selectButton('Login')->form();
		$crawler = $client->submit($form, array('member_no' => 'AA0001', 'password' => 'secret'));
		$crawler = $client->request('GET', url('admin'));
		$link    = $crawler->selectLink('下載打卡記錄')->link();

		$crawler = $client->click($link);
		return $crawler->html();
	}
}
