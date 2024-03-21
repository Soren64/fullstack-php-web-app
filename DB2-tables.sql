create table account
	(email		varchar(50),
	 password	varchar(20) not null,
	 type		varchar(20),
	 primary key(email)
	);


create table department
	(dept_name	varchar(100), 
	 location	varchar(100), 
	 primary key (dept_name)
	);

create table instructor
	(instructor_id		varchar(10),
	 instructor_name	varchar(50) not null,
	 title 			varchar(30),
	 dept_name		varchar(100), 
	 email			varchar(50) not null,
	 primary key (instructor_id)
	);


create table student
	(student_id		varchar(10), 
	 name			varchar(20) not null, 
	 email			varchar(50) not null,
	 dept_name		varchar(100), 
	 primary key (student_id),
	 foreign key (dept_name) references department (dept_name)
		on delete set null
	);

create table PhD
	(student_id			varchar(10), 
	 qualifier			varchar(30), 
	 proposal_defence_date		date,
	 dissertation_defence_date	date, 
	 primary key (student_id),
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table master
	(student_id		varchar(10), 
	 total_credits		int,	
	 primary key (student_id),
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table undergraduate
	(student_id		varchar(10), 
	 total_credits		int,
	 class_standing		varchar(10)
		check (class_standing in ('Freshman', 'Sophomore', 'Junior', 'Senior')), 	
	 primary key (student_id),
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table classroom
	(classroom_id 		varchar(8),
	 building		varchar(15) not null,
	 room_number		varchar(7) not null,
	 capacity		numeric(4,0),
	 primary key (classroom_id)
	);

create table time_slot
	(time_slot_id		varchar(8),
	 day			varchar(10) not null,
	 start_time		time not null,
	 end_time		time not null,
	 primary key (time_slot_id)
	);

create table course
	(course_id		varchar(20), 
	 course_name		varchar(50) not null, 
	 credits		numeric(2,0) check (credits > 0),
	 primary key (course_id)
	);

create table section
	(course_id		varchar(20),
	 section_id		varchar(10), 
	 semester		varchar(6)
			check (semester in ('Fall', 'Winter', 'Spring', 'Summer')), 
	 year			numeric(4,0) check (year > 1990 and year < 2100), 
	 instructor_id		varchar(10),
	 classroom_id   	varchar(8),
	 time_slot_id		varchar(8),	
	 primary key (course_id, section_id, semester, year),
	 foreign key (course_id) references course (course_id)
		on delete cascade,
	 foreign key (instructor_id) references instructor (instructor_id)
		on delete set null,
	 foreign key (time_slot_id) references time_slot(time_slot_id)
		on delete set null
	);

create table prereq
	(course_id		varchar(20), 
	 prereq_id		varchar(20) not null,
	 primary key (course_id, prereq_id),
	 foreign key (course_id) references course (course_id)
		on delete cascade,
	 foreign key (prereq_id) references course (course_id)
	);

create table advise
	(instructor_id		varchar(8),
	 student_id		varchar(10),
	 start_date		date not null,
	 end_date		date,
	 primary key (instructor_id, student_id),
	 foreign key (instructor_id) references instructor (instructor_id)
		on delete  cascade,
	 foreign key (student_id) references PhD (student_id)
		on delete cascade
);

create table TA
	(student_id		varchar(10),
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (student_id) references PhD (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade
);

create table masterGrader
	(student_id		varchar(10),
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (student_id) references master (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade
);

create table undergraduateGrader
	(student_id		varchar(10),
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (student_id) references undergraduate (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade
);

create table take
	(student_id		varchar(10), 
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 grade		    	varchar(2)
		check (grade in ('A+', 'A', 'A-','B+', 'B', 'B-','C+', 'C', 'C-','D+', 'D', 'D-','F')), 
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade,
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);



insert into account (email, password, type) values ('admin@uml.edu', '123456', 'admin');
insert into account (email, password, type) values ('dbadams@cs.uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('slin@cs.uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('Yelena_Rykalova@uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('Johannes_Weis@uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('Charles_Wilkes@uml.edu', '123456', 'instructor');

insert into course (course_id, course_name, credits) values ('COMP1010', 'Computing I', 3);
insert into course (course_id, course_name, credits) values ('COMP1020', 'Computing II', 3);
insert into course (course_id, course_name, credits) values ('COMP2010', 'Computing III', 3);
insert into course (course_id, course_name, credits) values ('COMP2040', 'Computing IV', 3);

insert into department (dept_name, location) value ('Miner School of Computer & Information Sciences', 'Dandeneau Hall, 1 University Avenue, Lowell, MA 01854');

insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('1', 'David Adams', 'Teaching Professor', 'Miner School of Computer & Information Sciences','dbadams@cs.uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('2', 'Sirong Lin', 'Associate Teaching Professor', 'Miner School of Computer & Information Sciences','slin@cs.uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('3', 'Yelena Rykalova', 'Associate Teaching Professor', 'Miner School of Computer & Information Sciences', 'Yelena_Rykalova@uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('4', 'Johannes Weis', 'Assistant Teaching Professor', 'Miner School of Computer & Information Sciences','Johannes_Weis@uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('5', 'Tom Wilkes', 'Assistant Teaching Professor', 'Miner School of Computer & Information Sciences','Charles_Wilkes@uml.edu');

insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS1', 'MoWeFr', '11:00:00', '11:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS2', 'MoWeFr', '12:00:00', '12:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS3', 'MoWeFr', '13:00:00', '13:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS4', 'TuTh', '11:00:00', '12:15:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS5', 'TuTh', '12:30:00', '13:45:00');

insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section101', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section102', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section103', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section104', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1020', 'Section101', 'Spring', 2024);
insert into section (course_id, section_id, semester, year) value ('COMP1020', 'Section102', 'Spring', 2024);
insert into section (course_id, section_id, semester, year) value ('COMP2010', 'Section101', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP2010', 'Section102', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP2040', 'Section201', 'Spring', 2024);




insert into account (email, password, type) values ('Lucca_Nelson@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('Nick_Matsuda@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('Jack_Fallon@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s1@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s2@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s3@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s4@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s5@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s6@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s7@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s8@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s9@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('s10@uml.edu', '123456', 'student');

insert into account (email, password, type) values ('m1@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('m2@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('m3@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('m4@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('m5@uml.edu', '123456', 'student');

insert into account (email, password, type) values ('p1@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('p2@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('p3@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('p4@uml.edu', '123456', 'student');
insert into account (email, password, type) values ('p5@uml.edu', '123456', 'student');

insert into student (student_id, name, email, dept_name) values ('01617595', 'Lucca Nelson', 'Lucca_Nelson@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('01000000', 'Nick Matsuda', 'Nick_Matsuda@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('01111111', 'Jack Fallon', 'Jack_Fallon@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('001', 's1', 's1@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('002', 's2', 's2@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('003', 's3', 's3@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('004', 's4', 's4@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('005', 's5', 's5@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('006', 's6', 's6@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('007', 's7', 's7@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('008', 's8', 's8@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('009', 's9', 's9@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('010', 's10', 's10@uml.edu', 'Miner School of Computer & Information Sciences');

insert into student (student_id, name, email, dept_name) values ('101', 'm1', 'm1@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('102', 'm2', 'm2@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('103', 'm3', 'm3@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('104', 'm4', 'm4@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('105', 'm5', 'm5@uml.edu', 'Miner School of Computer & Information Sciences');

insert into student (student_id, name, email, dept_name) values ('201', 'p1', 'p1@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('202', 'p2', 'p2@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('203', 'p3', 'p3@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('204', 'p4', 'p4@uml.edu', 'Miner School of Computer & Information Sciences');
insert into student (student_id, name, email, dept_name) values ('205', 'p5', 'p5@uml.edu', 'Miner School of Computer & Information Sciences');

insert into undergraduate (student_id, total_credits, class_standing) values ('01617595', 12, 'Senior');
insert into undergraduate (student_id, total_credits, class_standing) values ('01000000', 0, 'Senior');
insert into undergraduate (student_id, total_credits, class_standing) values ('01111111', 0, 'Senior');
insert into undergraduate (student_id, total_credits, class_standing) values ('001', 3, 'Freshman');
insert into undergraduate (student_id, total_credits, class_standing) values ('002', 3, 'Freshman');
insert into undergraduate (student_id, total_credits, class_standing) values ('003', 3, 'Freshman');
insert into undergraduate (student_id, total_credits, class_standing) values ('004', 3, 'Freshman');
insert into undergraduate (student_id, total_credits, class_standing) values ('005', 3, 'Sophomore');
insert into undergraduate (student_id, total_credits, class_standing) values ('006', 3, 'Sophomore');
insert into undergraduate (student_id, total_credits, class_standing) values ('007', 3, 'Sophomore');
insert into undergraduate (student_id, total_credits, class_standing) values ('008', 3, 'Junior');
insert into undergraduate (student_id, total_credits, class_standing) values ('009', 3, 'Junior');
insert into undergraduate (student_id, total_credits, class_standing) values ('010', 3, 'Senior');

insert into master (student_id, total_credits) values ('101', 0);
insert into master (student_id, total_credits) values ('102', 0);
insert into master (student_id, total_credits) values ('103', 0);
insert into master (student_id, total_credits) values ('104', 0);
insert into master (student_id, total_credits) values ('105', 0);

insert into PhD (student_id, qualifier, proposal_defence_date, dissertation_defence_date) values ('201', 'test', '1-1-2024', '1-1-2024');
insert into PhD (student_id, qualifier, proposal_defence_date, dissertation_defence_date) values ('202', 'test', '1-1-2024', '1-1-2024');
insert into PhD (student_id, qualifier, proposal_defence_date, dissertation_defence_date) values ('203', 'test', '1-1-2024', '1-1-2024');
insert into PhD (student_id, qualifier, proposal_defence_date, dissertation_defence_date) values ('204', 'test', '1-1-2024', '1-1-2024');
insert into PhD (student_id, qualifier, proposal_defence_date, dissertation_defence_date) values ('205', 'test', '1-1-2024', '1-1-2024');

insert into classroom (classroom_id, building, room_number, capacity) values ('CR0', 'test', 'test', 0);
insert into classroom (classroom_id, building, room_number, capacity) values ('CR1', 'SHA', '310', 15);

update section set instructor_id = '2', classroom_id = 'CR1', time_slot_id = 'TS1' where course_id = 'COMP1010' and section_id = 'Section101';
update section set instructor_id = '2', classroom_id = 'CR1', time_slot_id = 'TS2' where course_id = 'COMP1010' and section_id = 'Section102';

update section set instructor_id = '2', classroom_id = 'CR1', time_slot_id = 'TS1' where course_id = 'COMP1020' and section_id = 'Section101';
update section set instructor_id = '2', classroom_id = 'CR1', time_slot_id = 'TS2' where course_id = 'COMP1020' and section_id = 'Section102';

update section set instructor_id = '5', classroom_id = 'CR1', time_slot_id = 'TS4' where course_id = 'COMP2040' and section_id = 'Section201';

insert into take (student_id, course_id, section_id, semester, year, grade) values ('01617595', 'COMP2040', 'Section201', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('01617595', 'COMP2010', 'Section101', 'Fall', 2023, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('01617595', 'COMP1020', 'Section101', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('01617595', 'COMP1010', 'Section101', 'Fall', 2023, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('01000000', 'COMP2040', 'Section201', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('01111111', 'COMP2040', 'Section201', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('001', 'COMP1020', 'Section101', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('002', 'COMP1020', 'Section101', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('003', 'COMP1020', 'Section101', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('004', 'COMP1020', 'Section101', 'Spring', 2024, 'B');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('005', 'COMP1020', 'Section101', 'Spring', 2024, 'C+');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('006', 'COMP1020', 'Section101', 'Spring', 2024, 'C');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('007', 'COMP1020', 'Section101', 'Spring', 2024, 'C-');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('008', 'COMP1020', 'Section101', 'Spring', 2024, 'C-');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('009', 'COMP1020', 'Section101', 'Spring', 2024, 'D');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('010', 'COMP1020', 'Section101', 'Spring', 2024, 'F');

insert into take (student_id, course_id, section_id, semester, year, grade) values ('001', 'COMP2040', 'Section201', 'Spring', 2024, 'A');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('002', 'COMP2040', 'Section201', 'Spring', 2024, 'B');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('003', 'COMP2040', 'Section201', 'Spring', 2024, 'C');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('004', 'COMP2040', 'Section201', 'Spring', 2024, 'D');
insert into take (student_id, course_id, section_id, semester, year, grade) values ('005', 'COMP2040', 'Section201', 'Spring', 2024, 'F');

