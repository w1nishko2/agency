-- Скрипт для очистки плохих данных из таблицы pages

-- 1. Очистить content_map (сбросить все сохраненные inline-изменения)
UPDATE pages SET content_map = NULL;

-- 2. Очистить экранированный HTML из description для страницы "about"
-- Если нужно очистить description от экранированных тегов, раскомментируйте следующую строку:
-- UPDATE pages SET description = NULL WHERE slug = 'about';

-- 3. Проверить результаты
SELECT id, slug, title, 
       SUBSTRING(description, 1, 100) as description_preview,
       content_map 
FROM pages;
