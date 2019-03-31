document.getElementsByTagName("button")[0].onclick = logout;

function logout(event) {
    event.target.parentNode.submit();
}