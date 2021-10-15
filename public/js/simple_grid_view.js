var grid_view_container = document.querySelector(".simple-grid-view-container");

/**
 * Функция установки AJAX-обработчик на кнопки навигации
 */
function tuneNavigationButtons()
{
    setNavigationButton(".simple-grid-view-previous-button");
    setNavigationButton(".simple-grid-view-next-button");
}

/**
 * Функция установки AJAX-обработчика на конкретную кнопку
 * @param {type} navigation_button_selector  Селектор кнопки
 */
function setNavigationButton(navigation_button_selector) 
{
    var navigation_button = document.querySelector(navigation_button_selector);
    
    if (navigation_button!== null) {
        navigation_button.onclick = function(event) {
            event.preventDefault();
            actionGridView(navigation_button.href);
        }
    }
}

/**
 * AJAX-функция обработки логики GridView
 * 
 * @param {string} href  Путь URL для AJAX-обновления (берется из внутренней логики GridView)
 * @returns {undefined}  Ничего не возвращаем 
 */
function actionGridView(href) 
{	
    var xhttp = new XMLHttpRequest();
    xhttp.open('GET', href, true);
    xhttp.send();

    xhttp.onreadystatechange = function() {

        if ( this.readyState != 4 ) { 
        
            return;
            
        } else {
            
            if (this.responseText) {
                grid_view_container.innerHTML = this.responseText;
            }
            
            tuneNavigationButtons();
            return;
        }
    }
}

tuneNavigationButtons();
