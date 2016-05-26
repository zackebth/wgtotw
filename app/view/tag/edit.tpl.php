
<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
        <input type=hidden name="session" value="<?=$session?>">
        <input type=hidden name="id" value="<?=$id?>">
        <fieldset>
        <legend>Edit your comment</legend>
        
        <p class='name'><label>Name: *<br/><input type='text' name='name' value='<?=$name?>' required/></label></p>
        <p class='web'><label>Homepage:<br/><input type='url' name='web' value='<?=$web?>'/></label></p>
        <div class='clear'></div>
        <p class='email'><label>Email:<br/><input class='email' type='email' name='mail' value='<?=$mail?>' /></label></p>
        <p class='text'><label>Comment: *<br/><textarea class='comment' name='content' required><?=$content?></textarea></label></p>
        <p class=buttons>
            <input type='submit' name='doSave' value='Save' onClick="this.form.action = '<?=$this->url->create('comment/save')?>'"/>
            <input type='reset' value='Reset'/>
                  </p>
        <output><?=$output?></output>
        </fieldset>
    </form>
</div>
