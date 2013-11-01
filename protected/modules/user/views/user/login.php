<?php
$this->pageTitle = UserModule::t("Login") . ' - ' . Yii::app()->name;
$this->breadcrumbs = array(
    UserModule::t("Login"),
);

//$this->h1 = UserModule::t("Login");
?>

<?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

    <div class="success">
        <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
    </div>

<?php endif; ?>

    <div class="form">

        <?php /** @var BootActiveForm $form */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'verticalForm',
            'htmlOptions' => array('class' => 'well'),
        )); ?>

        <div class="row">
            <p><?php echo CHtml::errorSummary($model); ?></p>
        </div>
        <div class="row">
            <legend><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></legend>
        </div>

        <?php /*
        <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
*/
        ?>

        <div class="row">
            <?php echo $form->textFieldRow($model, 'username', array('class' => 'span3')); ?>
            <?php /*echo CHtml::activeLabelEx($model, 'username'); ?>
            <?php echo CHtml::activeTextField($model, 'username') */
            ?>
        </div>

        <div class="row">
            <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3')); ?>
            <?php /*echo CHtml::activeLabelEx($model, 'password'); ?>
            <?php echo CHtml::activePasswordField($model, 'password') */
            ?>
        </div>

        <div class="row">
            <p class="hint">
                <?php echo CHtml::link(UserModule::t("Register"), Yii::app()->getModule('user')->registrationUrl); ?>
                | <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl); ?>
            </p>
        </div>

        <div class="row">
            <?php echo $form->checkboxRow($model, 'rememberMe'); ?>
            <?php /*echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
            <?php echo CHtml::activeLabelEx($model, 'rememberMe'); */
            ?>
        </div>

        <div class="row submit">
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => UserModule::t("Login"))); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->


<?php
$form = new CForm(array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
            'maxlength' => 32,
        ),
        'password' => array(
            'type' => 'password',
            'maxlength' => 32,
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        )
    ),

    'buttons' => array(
        'login' => array(
            'type' => 'submit',
            'label' => 'Login',
        ),
    ),
), $model);
?>