function getTabs(){
    document.querySelectorAll(".selection").forEach(button => {
        button.addEventListener("click", () => {
            const tabs = button.parentElement;
            const tabContainer = tabs.parentElement;
            const tabNum = button.dataset.forTab;
            const selectedTab = tabContainer.querySelector(`.tabContent[data-tab="${tabNum}"]`);

            tabs.querySelectorAll(".selection").forEach(button => {
                button.classList.remove("selection-active");
            });
            tabContainer.querySelectorAll(".tabContent").forEach(tab => {
                tab.classList.remove("tab-active");
            });

            button.classList.add("selection-active");
            selectedTab.classList.add("tab-active");
        });
    });
}
document.addEventListener("DOMContentLoaded", () => {
    getTabs();
    
    document.querySelectorAll(".tabs").forEach(tabContainer => {
        tabContainer.querySelector(".tabOption .selection").click();
    });
});