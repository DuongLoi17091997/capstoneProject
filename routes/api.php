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
    Route::get('getUserByToken/{token}', 'UserAccountsController@getAccountByToken');
    Route::post('reset-password', 'LoginController@sentMailtoResetPassword');
    Route::post('reset-password-request', 'LoginController@sendTokenResetPassword');

    Route::group(['prefix'=>'dashboard'], function(){
         //Users Api
         Route::get('user-accounts/{accountType}', 'UserAccountsController@getAllUserAccounts');
         Route::get('get-teachers', 'UserAccountsController@GetAllTeachers');
         Route::get('user-accounts/get-account/{id}', 'UserAccountsController@getAccountByID');
         Route::put('user-accounts/handle-update/{id}', 'UserAccountsController@handleUpdateAccount');
         Route::post('user-accounts/update-avatar', 'UserAccountsController@handleUpdateAvatar');

         Route::post('user-accounts/change-password', 'UserAccountsController@changePassword');

         Route::put('user-accounts/handle-disable/{id}', 'UserAccountsController@handlerDisableAccount');
         Route::put('user-accounts/handle-enable/{id}', 'UserAccountsController@handlerEnableAccount');

         //Grades Api
         Route::get('grades', 'GradesController@getAllgrades');
         Route::get('get-grade-by-id/{id}', 'GradesController@getGradeById');
         Route::post('create-grade', 'GradesController@createGrade');
         Route::put('handle-edit-grade/{id}', 'GradesController@handleEdit');
         Route::put('disable-grade/{id}', 'GradesController@handleDisableGrade');
         Route::put('enable-grade/{id}', 'GradesController@handleEnableGrade');

         //Subject Api
         Route::post('search-subject-by-class', 'SubjectsController@getSubjectByClass');
         Route::get('get-all-subjects', 'SubjectsController@getAllSubjects');
         Route::post('create-subject', 'SubjectsController@createSubject');
         Route::get('get-subject-by-id/{id}', 'SubjectsController@getEdit');
         Route::put('handle-edit-subject/{id}', 'SubjectsController@handleEdit');
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
         Route::get('search-exame-history-by-exame-code/{id}', 'ExaminationHistoryController@getExamHistoryByExamId');
         Route::put('update-exam-history-result/{id}', 'ExaminationHistoryController@updateExamHistoryResultById');
         Route::put('add-comments-history-result/{id}', 'ExaminationHistoryController@addComments');

         Route::post('create-exam-history', 'ExaminationHistoryController@createExamHistory');

         Route::get('search-exame-history-by-user/{token}', 'ExaminationHistoryController@getExameHistoryByUser');
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
         Route::get('get-question/{id}', 'QuestionsController@getQuestionByID');
         Route::put('edit-question/{id}', 'QuestionsController@handleEditQuestion');
         Route::put('disable-question/{id}', 'QuestionsController@handleDisableQuestion');
         Route::put('enable-question/{id}', 'QuestionsController@handleEnableQuestion');

         //Anwser Api
         Route::post('save-anwser', 'QuestionAnswerController@saveAnwser');
         Route::get('get-anwser/{id}', 'QuestionAnswerController@getAnwserById');
         Route::put('edit-question-awnser/{id}', 'QuestionAnswerController@handleEditAwnserById');


         //Add Question to Examination Api
         Route::get('add-question-to-exame/{id}', 'QuestiontoExameController@addQuestionToExame');
         Route::post('get-question-belongs-to-exame', 'QuestiontoExameController@getAllQuestionsByExam');
         Route::post('save-question', 'QuestiontoExameController@saveAnwser');
         Route::post('save-exam', 'QuestiontoExameController@saveExam');


         //Api Assign Exam to Teacher
         Route::get('get-list-assign-exam/{id}', 'AssignedExamTeacherController@getAssignExamByTeacherId');
         Route::post('assign-exam', 'AssignedExamTeacherController@assignExamtoTeacher');

    });

});

