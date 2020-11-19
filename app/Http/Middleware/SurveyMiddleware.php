<?php

namespace App\Http\Middleware;

use App\Models\CommunityUser;
use App\Models\Survey;
use Closure;
use Request;
use Route;

class SurveyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type = null)
    {
        $controller = Request::route()->getController();

        if ($type == "survey") {
            $survey = Survey::where('url', Route::input('url'))->firstOrFail();

            if (isset($_GET["user_number"])) {
                $communityuser = CommunityUser::where('user_number', $_GET["user_number"])->first();
                if ($communityuser) {
                    $survey_visitor = $communityuser->survey_visitors->where('survey_id', $survey->survey_id)->first();
                    if ($survey_visitor) {
                        view()->share(['user_answered' => 'ဒီ Survey က ဖြေဆိုပီးသားဖြစ်ပါသည်']);
                    }
                    $controller->h_communityuser = $communityuser;
                } else {
                    abort('404');
                }
            }

            $controller->h_survey = $survey;
            view()->share(["h_survey" => $survey]);
            return $next($request);
        }
    }
}
