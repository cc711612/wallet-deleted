<?php

namespace App\Concerns\Databases\Macros;

use App\Concerns\Databases\Contracts\Request as RequestContract;
use App\Modules\Agreements\Databases\Services\AgreementAdminForReportService;
use App\Modules\Books\Databases\Services\BookHrService;
use App\Modules\Courses\Databases\Services\CourseHrService;
use App\Modules\Chapters\Databases\Entities\ChapterEntity;
//use App\Modules\Courses\Databases\Services\CourseAdminListForAgreementService;
use App\Modules\Identifications\AgreementStatus\Supports\AgreementSupport;
use App\Modules\Members\Databases\Services\MemberAdminService;
use App\Modules\Reports\Databases\Services\ReportAdminService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Arr;
use App\Concerns\Commons\Traits\AgreementDashboardTrait;
use App\Modules\Clients\Databases\Services\ClientHrService;

abstract class ReportMacro
{
    use AgreementDashboardTrait;
    protected $CourseListForAgreementService;

    public function getBookReport(RequestContract $request , int $page_count = 50)
    {
        $Result = [];
        try {
            $client_id=$request->client_id;
            //先取出合約
            $Recourse = $this->setClientId(Arr::get($request, 'client_id'))->handleRecourse();

            $book_ids = $Recourse['book'];
            $Members = (new ClientHrService())
                ->setRequest($request->toArray())
                ->getMemberIdsByClient($client_id);

            $member_ids = Arr::get($Members,'members',collect([]))->pluck('id')->toArray();

            if (count($book_ids) > 0  && count($member_ids) > 0) {
                $Result = app(ReportAdminService::class)->getBookWatchReport($book_ids, $member_ids,$request, $page_count);
            }

        } catch (\Exception $e) {
            Log::channel('slack')->critical($e);
        }


        return $Result;
    }

    /**
     * @param \App\Concerns\Databases\Contracts\Request $request
     * @param int                                       $page_count
     *
     * @return array|null
     * @Author  : Roy
     * @DateTime: 2021/1/28 下午 05:02
     */
    public function getCourseReport( RequestContract $request ,int $page_count = 20)
    {
        $Result = [
            'Reports'      => [],
            'Members'      => [],
            'Courses'      => [],
            'CourseEntity' => ['id' => 0],
        ];
        try {

            $course_id = $request->search['course_id'];
            $client_id = $request->client_id;


            $Recourse = $this->setClientId(Arr::get($request, 'client_id'))->handleRecourse();
            $course_ids = $Recourse['course'];

            $check_course_id = ( in_array($course_id, $course_ids) === false || is_null($course_id)) && empty($course_ids) === false;

            # 0 代表全部
            if ($check_course_id && $course_id !== "0") {
                $course_id = $course_ids[0];
            }

            //完全沒有課程id就回傳空的
            if (is_null($course_id)) {
                return $Result;
            }

            //取得所有課程名稱
            $CoursesData = $this->CourseListForAgreementService->getForReportByIds($course_ids,$course_id);

            $Result['Courses'] = $CoursesData['Courses'];
            $CourseEntity = $CoursesData['CourseEntity'];

            $Members = (new ClientHrService())
                ->setRequest($request->toArray())
                ->getMemberIdsByClient($client_id);

            $member_ids = Arr::get($Members,'members',collect([]))->pluck('id')->toArray();

            $chapter_ids = [];
            if(is_null($CourseEntity) == false){
                $chapter_ids = $CourseEntity->chapters->pluck('id')->toArray();
            }

            if (count($member_ids) > 0) {
                $Result['Members'] = app(MemberAdminService::class)->getForReportAgreementMacro($member_ids, $page_count);
            }

            if (count($chapter_ids) > 0 && count($member_ids) > 0) {
                $Result['Reports'] = app(ReportAdminService::class)->getChapterWatchReport($chapter_ids, $member_ids,$request);
            }
            //開始組合結果
            $Result['CourseEntity'] = $CourseEntity;
        } catch (\Exception $e) {
//            dd($e);
            Log::critical($e);
        }

        return $Result;
    }
}
