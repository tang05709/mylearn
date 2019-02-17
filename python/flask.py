#wtf select 添加顶级栏目选项
#form
parent_id = SelectField('parent_id', validators = [InputRequired()])
#view
form.parent_id.choices = [('','请选择'), (0, '顶级栏目')] + [(g.id, g.name) for g in find_taxons()]
# 或者
form.parent_id.choices.insert(0, ('', '请选择'))
form.parent_id.choices.insert(1, (0, '顶级栏目'))
