<?php

use common\models\constants\EventPriorityConstant;
use common\models\constants\FieldStatusConstant;
use common\models\constants\NotificationConstant;
use common\models\constants\PostStatusConstant;
use common\models\constants\ReactionTypeConstant;
use common\models\constants\StatusConstant;
use common\models\constants\ThreadTypeConstant;
use common\models\constants\UserRoleConstant;
use common\models\constants\UserStatusConstant;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%session}}', [
            'id' => $this->primaryKey(),
            'expire' => $this->integer(),
            'data' => 'longblob',
        ], $tableOptions);

        $this->createTable('{{%config_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%app_config}}', [
            'id' => $this->primaryKey(),
            'config_typeId' => $this->integer(),
            'value' => $this->text(),
            'status' => $this->smallInteger()->notNull()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //App config foreign key
        $this->addForeignKey('FK_config_type', 'app_config', 'config_typeId', 'config_type', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text()->notNull(),
            'short_description' => $this->string()->notNull(),
            'phone_number' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            '_username' => $this->string()->notNull()->unique(),
            '_password' => $this->text()->notNull(),
            'full_name' => $this->string(),
            'email' => $this->string(50)->unique(),
            'address' => $this->text(),
            'phone_number' => $this->string(20),
            'birthday' => $this->string(10),
            'avatar' => $this->text(),
            'gender' => $this->string(10),
            'introduction' => $this->text(),
            'short_introduction' => $this->string(20),
            'facebook_link' => $this->text(),
            'skype_link' => $this->text(),
            'github_link' => $this->text(),
            'youtube_link' => $this->text(),
            'departmentId' => $this->integer(),
            'role' => $this->smallInteger()->defaultValue(UserRoleConstant::USER),
            'status' => $this->smallInteger()->defaultValue(UserStatusConstant::ACTIVE),
            'auth_key' => $this->string(32)->notNull(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //User foreign key
        $this->addForeignKey('FK_user_department', 'user', 'departmentId', 'department', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'source' => $this->string(),
            'sourceId' => $this->string(),
        ], $tableOptions);

        //Auth foreign key
        $this->addForeignKey('FK_auth_user', 'auth', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%account_privacy}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'field_name' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(FieldStatusConstant::VISIBLE),
            'created_at' => $this->string(),
        ], $tableOptions);

        //Account privacy foreign key
        $this->addForeignKey('FK_user_privacy', 'account_privacy', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%firebase_token}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'deviceId' => $this->string(),
            'deviceToken' => $this->string(),
            'deviceType' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'short_description' => $this->string(),
            'description' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'image' => $this->text(),
            'moderatorId' => $this->integer(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Category foreign key
        $this->addForeignKey('FK_category_moderator', 'category', 'moderatorId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%topic}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'short_description' => $this->string(),
            'description' => $this->text(),
            'categoryId' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'image' => $this->text(),
            'moderatorId' => $this->integer(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Category foreign key
        $this->addForeignKey('FK_topic_category', 'topic', 'categoryId', 'category', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_topic_moderator', 'topic', 'moderatorId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%thread}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'short_description' => $this->string(),
            'description' => $this->text(),
            'topicId' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'type' => $this->smallInteger()->defaultValue(ThreadTypeConstant::PUBLIC_THREAD),
            'image' => $this->text(),
            'moderatorId' => $this->integer(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Category foreign key
        $this->addForeignKey('FK_thread_topic', 'thread', 'topicId', 'topic', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_thread_moderator', 'thread', 'moderatorId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%group_member}}', [
            'id' => $this->primaryKey(),
            'groupId' => $this->integer(),
            'memberId' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'creatorId' => $this->integer(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Category foreign key
        $this->addForeignKey('FK_member_thread', 'group_member', 'groupId', 'thread', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_member_user', 'group_member', 'memberId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_member_creator', 'group_member', 'creatorId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'content' => $this->text()->notNull(),
            'short_description' => $this->string(),
            'threadId' => $this->integer(),
            'parentId' => $this->integer(),
            'level' => $this->smallInteger(),
            'authorId' => $this->integer()->notNull(),
            'view_count' => $this->integer(),
            'reply_count' => $this->integer(),
            'like_count' => $this->integer(),
            'tagged_userId' => $this->integer(),
            'last_activity' => $this->string(),
            'is_approved' => $this->smallInteger()->defaultValue(PostStatusConstant::PENDING),
            'status' => $this->smallInteger()->defaultValue(UserStatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Post foreign key
        $this->addForeignKey('FK_post_parentId', 'post', 'parentId', 'post', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_post_thread', 'post', 'threadId', 'thread', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_post_author', 'post', 'authorId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_post_taggedUser', 'post', 'tagged_userId', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'parentId' => $this->integer(),
            'userId' => $this->integer(),
            'actorId' => $this->integer(),
            'content' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'isSeen' => $this->smallInteger()->defaultValue(NotificationConstant::NOTIFICATION_UNSEEN),
            'url' => $this->string(),
            'type' => $this->smallInteger()->defaultValue(NotificationConstant::NOTIFICATION_TYPE_POST),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('FK_notification_userId', 'notification', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_notification_actorId', 'notification', 'actorId', 'user', 'id', 'CASCADE', 'CASCADE');
        
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'parentId' => $this->integer(),
            'file_type' => $this->string(),
            'file_extension' => $this->string(),
            'url' => $this->text(),
            'original_name' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //File foreign key
        $this->addForeignKey('FK_file_parentId', 'file', 'parentId', 'post', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%reaction}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'postId' => $this->integer(),
            'reaction_type' => $this->integer()->defaultValue(ReactionTypeConstant::REACTION_LIKE),
            'created_at' => $this->string()->notNull(),
        ], $tableOptions);

        //Reaction foreign key
        $this->addForeignKey('FK_reaction_user', 'reaction', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_reaction_post', 'reaction', 'postId', 'post', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%report_reason}}', [
            'id' => $this->primaryKey(),
            'reason' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'postId' => $this->integer(),
            'reasonId' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(PostStatusConstant::PENDING),
            'created_at' => $this->string()->notNull(),
            'approved_by' => $this->integer(),
        ], $tableOptions);

        //Report foreign key
        $this->addForeignKey('FK_report_user', 'report', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_report_post', 'report', 'postId', 'post', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_report_reason', 'report', 'reasonId', 'report_reason', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'start_date' => $this->string()->notNull(),
            'end_date' => $this->string()->notNull(),
            'description' => $this->text(),
            'short_description' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%event_attendance}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'eventId' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(PostStatusConstant::PENDING),
            'created_at' => $this->string()->notNull(),
            'priority' => $this->smallInteger()->defaultValue(EventPriorityConstant::PRIORITY_AVERAGE),
        ], $tableOptions);

        //Event attendance foreign key
        $this->addForeignKey('FK_attendance_user', 'event_attendance', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_attendance_event', 'event_attendance', 'eventId', 'event', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%test_level}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
            'time' => $this->integer(),
            'short_description' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%test}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'userId' => $this->integer(),
            'threadId' => $this->integer(),
            'levelId' => $this->integer(),
            'score' => $this->integer(),
            'can_modify' => $this->smallInteger(),
            'start_date' => $this->string(50),
            'end_date' => $this->string(50),
            'type' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Test foreign key
        $this->addForeignKey('FK_test_user', 'test', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_test_level', 'test', 'levelId', 'test_level', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_test_thread', 'test', 'threadId', 'thread', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%survey}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'surveyId' => $this->integer(),
            'is_remind' => $this->smallInteger(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Survey foreign key
        $this->addForeignKey('FK_survey_user', 'survey', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_survey_test', 'survey', 'surveyId', 'test', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'content' => $this->text(),
            'surveyId' => $this->integer(),
            'threadId' => $this->integer(),
            'score' => $this->integer(),
            'levelId' => $this->integer(),
            'picked_count' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Question foreign key
        $this->addForeignKey('FK_question_thread', 'question', 'threadId', 'thread', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_question_level', 'question', 'levelId', 'test_level', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_question_survey', 'question', 'surveyId', 'test', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%answer}}', [
            'id' => $this->primaryKey(),
            'questionId' => $this->integer(),
            'content' => $this->text(),
            'explanation' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'is_correct' => $this->smallInteger()->notNull(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Question foreign key
        $this->addForeignKey('FK_answer_question', 'answer', 'questionId', 'question', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%test_detail}}', [
            'id' => $this->primaryKey(),
            'testId' => $this->integer(),
            'questionId' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'answerId' => $this->integer(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Test detail foreign key
        $this->addForeignKey('FK_detail_test', 'test_detail', 'testId', 'test', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_detail_question', 'test_detail', 'questionId', 'question', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_detail_answer', 'test_detail', 'answerId', 'answer', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'label' => $this->string(50)->unique()->notNull(),
            'parentId' => $this->integer(),
            'level' => $this->smallInteger(),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'url' => $this->string(50),
            'icon' => $this->string(50),
            'icon_style' => $this->string(20),
            'level' => $this->smallInteger(),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        //Menu foreign key
        $this->addForeignKey('FK_menu_parent', 'menu', 'parentId', 'menu', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%advertisement}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50),
            'content' => $this->text(),
            'cover_image' => $this->string(255),
            'start_date' => $this->string(50),
            'end_date' => $this->string(50),
            'status' => $this->smallInteger()->defaultValue(StatusConstant::ACTIVE),
            'created_at' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->string(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
