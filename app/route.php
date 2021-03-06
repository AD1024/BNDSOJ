<?php

Route::pattern('username', '[a-zA-Z0-9_]{1,20}');
Route::pattern('id', '[1-9][0-9]{0,9}');
Route::pattern('contest_id', '[1-9][0-9]{0,9}');
Route::pattern('tab', '\S{1,20}');
Route::pattern('rand_str_id', '[0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ]{20}');
Route::pattern('upgrade_name', '[a-zA-Z0-9_]{1,50}');
Route::pattern('blog_username', '[a-zA-Z0-9_\-]{1,20}');

Route::group([
		'domain' => UOJConfig::$data['web']['main']['host']."|127.0.0.1"/*,
		'onload' => function() {
			UOJContext::setupBlog();
		}*/
	], function() {
		Route::any('/', '/index.php');
		Route::any('/problems', '/problem_set.php');
		Route::any('/problems/template', '/problem_set.php?tab=template');
		Route::any('/problem/{id}', '/problem.php');
		Route::any('/problem/{id}/statistics', '/problem_statistics.php');
		Route::any('/problem/{id}/manage/statement', '/problem_statement_manage.php');
		Route::any('/problem/{id}/manage/managers', '/problem_managers_manage.php');
		Route::any('/problem/{id}/manage/data', '/problem_data_manage.php');

		// AD1024 begin [redirect blog]
		/*
		Route::any('/blog/{blog_username}', '/subdomain/blog/index.php');
		Route::any('/blog/{blog_username}/archive', '/subdomain/blog/archive.php');
		Route::any('/blog/{blog_username}/aboutme', '/subdomain/blog/aboutme.php');
		Route::any('/blog/{blog_username}/click-zan', '/click_zan.php');
		Route::any('/blog/{blog_username}/blog/{id}', '/subdomain/blog/blog.php');
		Route::any('/blog/{blog_username}/slide/{id}', '/subdomain/blog/slide.php');
		Route::any('/blog/{blog_username}/blog/(?:{id}|new)/write', '/subdomain/blog/blog_write.php');
		Route::any('/blog/{blog_username}/slide/(?:{id}|new)/write', '/subdomain/blog/slide_write.php');
		Route::any('/blog/{blog_username}/{id}/delete', '/subdomain/blog/blog_delete.php');

		//dhxh begin
		Route::any('/blog/imgupload', '/subdomain/blog/blog_img_upload.php');
		//dhxh end

		// AD1024 end
		*/

		//Skqliao begin
		Route::any('/index_contest', '/index.php?tab=contest');
		Route::any('/index_problem', '/index.php?tab=problem');
		Route::any('/contest/{id}/rated', '/contest_inside.php?tab=rated');
		Route::any('/contest_ranklist', '/contest_ranklist.php?type=rating');
		//Skqliao end

		//dhxh begin
		Route::any('/problems/basic', '/problem_set.php?tab=basic');
		Route::any('/problems/harder', '/problem_set.php?tab=harder');
		Route::any('/problems/enhance', '/problem_set.php?tab=enhance');
		Route::any('/problem/{id}/manage/solution','/problem_solutions_manage.php');
		Route::any('/problem/{id}/manage/imgupload', '/problem_img_upload.php');
		Route::any('/problem/{id}/manage/imgmanage','/problem_img_manage.php');
		//Route::any('/the_pantheon','/the_pantheon.php');
		Route::any('/remoteoj','/remote_oj_problem_list.php');
		Route::any('/remoteoj/problem','/remote_oj_problem.php');
		Route::any('/remoteoj/submissions','/remote_oj_submissions_list.php');
		Route::any('/remoteoj/submission/{id}','/remote_oj_submission.php');
		Route::any('/super-manage/user-group', '/user_group_manage.php');
		Route::any('/super-manage/tmp-user', '/tmp_user_manage.php');
		Route::any('/super-manage/user-password-reset', '/user_password_reset.php');
		Route::any('/super-manage/register', '/register_manage.php');
		Route::any('/ajax/user-group-list', '/ajax/user_group_list.php');
		Route::any('/ajax/user-group-manage', '/ajax/user_group_manage.php');
		Route::any('/ajax/user-group-user-list', '/ajax/user_group_user_list.php');
		Route::any('/ajax/user-group-nuser-list', '/ajax/user_group_nuser_list.php');
		Route::any('/ajax/tmp-user-list', '/ajax/tmp_user_list.php');
		Route::any('/user/aclist/{username}', '/ac_list.php');
		Route::any('/homework/manage/list','/homework/homework_manage/homework_list.php');
		Route::any('/homework/manage/delete','/homework/homework_manage/delete_homework.php');
		Route::any('/homework/manage/edit','/homework/homework_manage/edit_homework.php');
		Route::any('/homework/manage/result','/homework/homework_manage/homework_result.php');
		Route::any('/homework/manage/details','/homework/homework_manage/homework_details.php');
		Route::any('/homework/manage/details/problemlist','/homework/homework_manage/homework_problem_list.php');
		Route::any('/homework/manage/details/usergrouplist','/homework/homework_manage/homework_usergroup_list.php');
		Route::any('/homework/manage/details/usergroupnlist','/homework/homework_manage/homework_usergroup_nlist.php');
		Route::any('/homework','/homework/homework/homework.php');
		Route::any('/homework/list','/homework/homework/homework_list.php');
		Route::any('/homework/detail','/homework/homework/homework_details.php');
		Route::any('/homework/result','/homework/homework/homework_result.php');
		Route::any('/contest/{id}/correctionstandings', '/contest_inside.php?tab=modify_standings');

		Route::any('/materials', '/material_set.php');
		Route::any('/material/{id}', '/material.php');
		Route::any('/material/{id}/manage/statement', '/material_statement_manage.php');
		Route::any('/material/{id}/manage/imgupload', '/material_img_upload.php');
		Route::any('/material/{id}/manage/imgmanage','/material_img_manage.php');
		//dhxh end

		// AD1024 begin
		Route::any('/homework/management','/homework/homework_manage/homework_management.php');
		Route::any('/homework/manage/add','/homework/homework_manage/add_homework.php');
		Route::any('/api/manage/password-reset','/api/api_password_reset.php');
		/*Route::any('/homework/user_manage','/homework/homework_manage/user_manage.php');
		Route::any('/homework/user_manage/{username}','/homework/homework_manage/user_manage_page.php');*/
		Route::any('/api/manage/user/group','/api/UserGroupManage.php');
		// AD1024 end
		
		Route::any('/contests', '/contests.php');
		Route::any('/contest/new', '/add_contest.php');
		Route::any('/contest/{id}', '/contest_inside.php');
		Route::any('/contest/{id}/registrants', '/contest_members.php');
		Route::any('/contest/{id}/register', '/contest_registration.php');
		Route::any('/contest/{id}/manage', '/contest_manage.php');
		Route::any('/contest/{id}/submissions', '/contest_inside.php?tab=submissions');
		Route::any('/contest/{id}/standings', '/contest_inside.php?tab=standings');
		Route::any('/contest/{contest_id}/problem/{id}', '/problem.php');
		Route::any('/contest/{contest_id}/problem/{id}/statistics', '/problem_statistics.php');
		
		Route::any('/fake-index', '/update.php');
		
		Route::any('/submissions', '/submissions_list.php');
		Route::any('/submission/{id}', '/submission.php');
		Route::any('/submission-status-details', '/submission_status_details.php');
		
		Route::any('/hacks', '/hack_list.php');
		Route::any('/hack/{id}', '/hack.php');
		
		Route::any('/blogs', '/blogs.php');
		Route::any('/blog/{id}', '/blog_show.php');
		
		Route::any('/announcements', '/announcements.php');
		
		Route::any('/faq', '/faq.php');
		Route::any('/ranklist', '/ranklist.php?type=rating');
		
		Route::any('/login', '/login.php');
		Route::any('/logout', '/logout.php');
		Route::any('/register', '/register.php');
		Route::any('/forgot-password', '/forgot_pw.php');
		Route::any('/reset-password', '/reset_pw.php');
		Route::any('/user/profile/{username}', '/user_info.php');
		Route::any('/user/modify-profile', '/change_user_info.php');
		Route::any('/user/msg', '/user_msg.php');
		Route::any('/user/system-msg', '/user_system_msg.php');
		Route::any('/super-manage(?:/{tab})?', '/super_manage.php');
		
		Route::any('/download.php', '/download.php');
		
		Route::any('/click-zan', '/click_zan.php');
		
		Route::any('/upgrade/up/{upgrade_name}', '/upgrade.php?type=up');
		Route::any('/upgrade/down/{upgrade_name}', '/upgrade.php?type=down');
		Route::any('/upgrade/latest', '/upgrade.php?type=latest');
	}
);

Route::group([
		'domain' => UOJConfig::$data['web']['main']['host'], 
		'onload' => function() {
			UOJContext::setupBlog();
		}
	], function() {
		Route::any('/blog/{blog_username}', '/subdomain/blog/index.php');
		Route::any('/blog/{blog_username}/archive', '/subdomain/blog/archive.php');
		Route::any('/blog/{blog_username}/aboutme', '/subdomain/blog/aboutme.php');
		Route::any('/blog/{blog_username}/click-zan', '/click_zan.php');
		Route::any('/blog/{blog_username}/blog/{id}', '/subdomain/blog/blog.php');
		Route::any('/blog/{blog_username}/slide/{id}', '/subdomain/blog/slide.php');
		Route::any('/blog/{blog_username}/blog/(?:{id}|new)/write', '/subdomain/blog/blog_write.php');
		Route::any('/blog/{blog_username}/slide/(?:{id}|new)/write', '/subdomain/blog/slide_write.php');
		Route::any('/blog/{blog_username}/blog/{id}/delete', '/subdomain/blog/blog_delete.php');

		//dhxh begin
		Route::any('/blog/{blog_username}/blog/imgupload', '/subdomain/blog/blog_img_upload.php');
		//dhxh end
	}
);

Route::post('/judge/submit', '/judge/submit.php');
Route::post('/judge/sync-judge-client', '/judge/sync_judge_client.php');

Route::post('/judge/download/submission/{id}/{rand_str_id}', '/judge/download.php?type=submission');
Route::post('/judge/download/tmp/{rand_str_id}', '/judge/download.php?type=tmp');
Route::post('/judge/download/problem/{id}', '/judge/download.php?type=problem');
