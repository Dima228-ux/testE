<?php

//namespace NamePlugin; поставил бы имя в зависимотсти от моего проекта

class NameApi
{

    public $api_url;

    public function actionGetVacansies($post, $vid = 0)
    {
        $ret = array();

        if (!is_object($post)) {
            return ['message' => 'Mistake in post'];
        }

        $page = 0;
               l1:
        $params = "status=all&id_user=" . $this->self_get_option('superjob_user_id') . "&with_new_response=0&order_field=date&order_direction=desc&page={$page}&count=100";
        $res = $this->api_send($this->api_url . '/hr/vacancies/?' . $params);
        $res_o = json_decode($res);

        if ($res !== false && is_object($res_o) && isset($res_o->objects)) {
            $answer = $this->foundVacansies($res_o, $ret, $vid, $page);
            if ($answer === false) {
                goto l1;
            }
            return $answer;
        } else {
            return ['message' => 'Error  resources is not object '];;
        }

        return ['message' => 'Error not found'];
    }

    private function foundVacansies($res_o, $ret, $vid, $page)
    {
        $found = false;
        $ret = array_merge($res_o->objects, $ret);
        if ($vid > 0) {
            // Для конкретной вакансии, иначе возвращаем все
            foreach ($res_o->objects as $key => $value) {
                if ($value->id == $vid) {
                    $found = $value;
                    break;
                }
            }
        }
        if ($found === false && $res_o->more) {
            $page++;
            return false;
        } else {
            if (is_object($found)) {
                return $found;
            } else {
                return $ret;
            }
        }
    }

    public function api_send()
    {
        return '';
    }

    public function self_get_option($option_name)
    {
        return '';
    }
}