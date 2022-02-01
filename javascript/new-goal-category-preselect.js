let goalForm = document.querySelector("form.goal");
goalForm.addEventListener("submit", onSubmitGoalForm);

function onSubmitGoalForm() {
    if (categoryId != -1) {
        let categoryIdInput = document.querySelector("input[name='categoryIdPreselect'");
        categoryIdInput.value = categoryId;
    }
}
