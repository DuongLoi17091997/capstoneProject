<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'admin', 'namespace'=>'App\Http\Controllers'], function(){
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');
    Route::post('getUserByToken', 'UserAccountsController@getAccountByToken');
    Route::post('reset-password', 'LoginController@sentMailtoResetPassword');
    Route::post('reset-password-request', 'LoginController@sendTokenResetPassword');

    Route::group(['prefix'=>'dashboard'], function(){
         //Users Api
         Route::get('user-accounts', 'UserAccountsController@getAllUserAccounts');
         Route::get('user-accounts/get-account/{id}', 'UserAccountsController@getAccountByID');
         Route::post('user-accounts/handle-update', 'UserAccountsController@handleUpdateAccount');
         Route::post('user-accounts/change-password', 'UserAccountsController@changePassword');

         Route::put('user-accounts/handle-disable/{id}', 'UserAccountsController@handlerDisableAccount');
         Route::put('user-accounts/handle-enable/{id}', 'UserAccountsController@handlerEnableAccount');

         //Grades Api
         Route::get('grades', 'GradesController@getAllgrades');
         Route::post('get-grade-by-id', 'GradesController@getGradeById');
         Route::post('create-grade', 'GradesController@createGrade');
         Route::post('handle-edit-grade', 'GradesController@handleEdit');
         Route::put('disable-grade/{id}', 'GradesController@handleDisableGrade');
         Route::put('enable-grade/{id}', 'GradesController@handleEnableGrade');

         //Subject Api
         Route::post('search-subject-by-class', 'SubjectsController@getSubjectByClass');
         Route::get('get-all-subjects', 'SubjectsController@getAllSubjects');
         Route::post('create-subject', 'SubjectsController@createSubject');
         Route::get('get-edit-subject/{id}', 'SubjectsController@getEdit');
         Route::post('handle-edit-subject', 'SubjectsController@handleEdit');
         Route::put('disable-subject/{id}', 'SubjectsController@handleDisableSubject');
         Route::put('enable-subject/{id}', 'SubjectsController@handleEnableSubject');


         //Examination Api
         Route::post('search-exame-by-subject', 'ExaminationController@getExamBySubject');
         Route::get('get-all-exame', 'ExaminationController@getAllExamWithPagination');
         Route::get('get-all-exam', 'ExaminationController@getAllExam');
         Route::get('get-exam-by-Id/{id}', 'ExaminationController@getExambyId');

         Route::post('create-exame', 'ExaminationController@createExame');
         Route::post('get-edit-exame', 'ExaminationController@getEdit');
         Route::post('handlde-edit-exame', 'ExaminationController@handleEdit');
         Route::put('disable-exame/{id}', 'ExaminationController@handleDisableExame');
         Route::put('enable-exame/{id}', 'ExaminationController@handleEnableExame');

         //Examination History Api
         Route::post('search-exame-history-by-exame-code', 'ExaminationHistoryController@getExamHistoryByExameId');
         Route::post('search-exame-history-by-user', 'ExaminationHistoryController@getExameHistoryByUser');
         Route::get('get-all-exame-histroy', 'ExaminationHistoryController@getAllExameHistory');
         Route::post('create-exame-history', 'ExaminationHistoryController@createExameHistory');
         Route::post('get-edit-exame-history', 'ExaminationHistoryController@getEdit');
         Route::post('handlde-edit-exame-history', 'ExaminationHistoryController@handleEdit');
         Route::put('disable-exame-history/{id}', 'ExaminationHistoryController@handleDisableExameHistory');
         Route::put('enable-exame-history/{id}', 'ExaminationHistoryController@handleEnableExameHistory');

         //Questions Api
         Route::post('search-question-by-name', 'QuestionsController@getQuestionByName');
         Route::post('search-question-by-subjectId', 'QuestionsController@getQuestionBySubjectId');
         Route::get('get-all-questions', 'QuestionsController@getAllQuestions');
         Route::post('create-question', 'QuestionsController@createQuestion');
         Route::post('get-edit-question', 'QuestionsController@getQuestionByID');
         Route::post('handlde-edit-question', 'QuestionsController@handleEditQuestion');
         Route::put('disable-question/{id}', 'QuestionsController@handleDisableQuestion');
         Route::put('enable-question/{id}', 'QuestionsController@handleEnableQuestion');

         //Add Question to Examination Api
         Route::get('add-question-to-exame/{id}', 'QuestiontoExameController@addQuestionToExame');
         Route::post('get-question-belongs-to-exame', 'QuestiontoExameController@getAllQuestionsByExam');
         Route::post('save-question', 'QuestiontoExameController@saveAnwser');
         Route::post('save-exam', 'QuestiontoExameController@saveExam');


    });

});

