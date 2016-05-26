/*
surveusez-05262016.sql
some potential sql statements to use for the surveysez app
*/

#retriveves all question for sruveu #1

select QuestionID, Question, Description  from srv_questions where SurveyID = 1

#retriveves all question for sruveu #1, plus title from Survey table 
select Title, QuestionID, Question, q.Description  from 
srv_questions as q inner join srv_surveys as s on 
q.SurveyID = s.SurveyID 
where q.SurveyID = 1