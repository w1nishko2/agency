/**
 * Загрузка и применение сохраненного inline-контента на публичных страницах
 */

document.addEventListener('DOMContentLoaded', async function() {
    // Проверяем, есть ли на странице data-page-id
    const pageContainer = document.querySelector('[data-page-id]');
    
    if (!pageContainer) {
        return; // Нет ID страницы - ничего не делаем
    }
    
    const pageId = pageContainer.dataset.pageId;
    
    try {
        // Загружаем сохраненный контент
        const response = await fetch(`/api/pages/${pageId}/content`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            return; // Ошибка при загрузке - игнорируем
        }
        
        const data = await response.json();
        
        if (!data.success || !data.content_map) {
            return; // Нет сохраненного контента
        }
        
        // Применяем сохраненный контент к элементам
        Object.keys(data.content_map).forEach(elementId => {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = data.content_map[elementId];
            }
        });
        
    } catch (error) {
        console.error('Error loading saved content:', error);
        // Молча игнорируем ошибки - не должны ломать страницу
    }
});
