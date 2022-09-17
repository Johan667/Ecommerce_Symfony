
let burger = document.querySelector("#burger")

let menu = [document.querySelector("#menu1"), document.querySelector("#menu2")]

let flexBreak = document.createElement('div')
flexBreak.style.flexBasis = "100%"
flexBreak.id = "flexbreak"

burger.addEventListener("click", function() {
    if (burger.classList.contains("fa-bars")) {
        burger.classList.replace("fa-bars", "fa-times")
        menu.forEach(element => {
            element.classList.remove("dnone")
            element.style.display = "flex"
        })
        insertAfter(burger.parentElement, flexBreak)
    } else {
        burger.classList.replace("fa-times", "fa-bars")
        menu.forEach(element => {
            element.classList.add("dnone")
            element.style.display = ""
        })
        flexBreak.remove()
    }
})

window.addEventListener('resize', function() {
    if (window.screen.width > 820 && document.querySelector("#flexbreak")) {
        flexBreak.remove()
    } else if (window.screen.width > 820 && menu[0].classList.contains("dnone") && menu[1].classList.contains("dnone")) {
        menu.forEach(element => {
            element.classList.remove("dnone")
            element.style.display = "flex"
        })
    } else if (window.screen.width == 820 && burger.classList.contains("fa-bars") && !menu[0].classList.contains("dnone") && !menu[1].classList.contains("dnone")) {
        menu.forEach(element => {
            element.classList.add("dnone")
            element.style.display = ""
        })
    }
});


