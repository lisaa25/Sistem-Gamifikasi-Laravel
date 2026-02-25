// button hapus di cart
document.addEventListener("DOMContentLoaded", function () {
    refreshCartBadge();

    const deleteButtons = document.querySelectorAll(".btn-danger");
    deleteButtons.forEach((button) => {
        button.addEventListener("click", function (event) {
            if (
                !confirm(
                    "Apakah Anda yakin ingin menghapus produk ini dari keranjang?"
                )
            ) {
                event.preventDefault();
            }
        });
    });
});

//merefresh badge cart
function refreshCartBadge() {
    fetch("/cart/count")
        .then((response) => response.json())
        .then((data) => {
            var cartBadge = document.querySelector(".shopping-cart-badge");
            if (cartBadge) {
                cartBadge.textContent = data.count;
            }
        })
        .catch((error) => console.error("Error:", error));
}
