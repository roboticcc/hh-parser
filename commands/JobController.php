<?php

namespace app\commands;

use app\models\ApiRepository;
use GuzzleHttp\Exception\GuzzleException;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Job;

class JobController extends Controller
{
    /**
     * This command parses needed data about jobs and sends them to the db
     *
     * @return int Exit code
     * @throws GuzzleException
     */
    public function actionParse(): int
    {
        $idArr = $this->getIdList();
        $idTotal = count($idArr);

        for ($i = 0; $i < $idTotal; $i++) {

            $job = new Job();

            if ($job->find()->where(['hh_id' => $idArr[$i]])->exists()) {
                continue;
            } else {
                $rawJob = ApiRepository::request('/vacancies/' . $idArr[$i]);

                $key_skills = [];
                $skillsCount = count($rawJob['key_skills']);

                for ($j = 0; $j < $skillsCount; $j++) {
                    $key_skills[] = $rawJob['key_skills'][$j]['name'];
                }

                $job->name = $rawJob['name'];
                $job->hh_id = $rawJob['id'];
                $job->key_skills = implode(', ', $key_skills);

                $job->save();
            }
        }

        return ExitCode::OK;
    }

    private function getIdList(): array
    {
        $rawList = [];

        for ($i = 0; $i < 20; $i++) {
            $result[] = ApiRepository::request('/vacancies?text=php&page=' . $i . '&per_page=100');
            for ($j = 0; $j < 99; $j++) {
                $rawList[] = $result[$i]['items'][$j];
            }
        }
        foreach ($rawList as $item) {
            $idList[] = $item['id'];
        }
        return $idList;
    }
}
