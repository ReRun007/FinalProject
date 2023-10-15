function incrementQuantity(input) {
        // เพิ่มค่าจำนวน
        const quantityInput = input.parentElement.querySelector('input[name="quantity"]');
        const currentQuantity = parseInt(quantityInput.value, 10);
        if (!isNaN(currentQuantity)) {
            quantityInput.value = currentQuantity + 1;
        }
    }

    function decrementQuantity(input) {
        // ลดค่าจำนวนแต่ไม่ต่ำกว่า 1
        const quantityInput = input.parentElement.querySelector('input[name="quantity"]');
        const currentQuantity = parseInt(quantityInput.value, 10);
        if (!isNaN(currentQuantity) && currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }