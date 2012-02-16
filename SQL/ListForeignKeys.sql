	select
2	    concat(table_name, '.', column_name) as 'foreign key',
3	    concat(referenced_table_name, '.', referenced_column_name) as 'references'
4	from
5	    information_schema.key_column_usage
6	where
7	    referenced_table_name is not null;