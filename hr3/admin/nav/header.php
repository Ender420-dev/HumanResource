<div class="row px-3 shadow-lg h-18 d-grid gap-3 d-flex align-items-center  ">
    <div class="col"><h2><?php echo $title; ?></h2></div>
    <div class="col text-end">
        <button id="theme-toggle" class="btn btn-outline-secondary">
            <i id="theme-icon" class="fas fa-moon"></i>
        </button>
    </div>
</div>

<script>
function setTheme(theme) {
    localStorage.setItem('theme', theme);
    document.documentElement.setAttribute('data-bs-theme', theme);
}
</script>

<style>
.h-18 {
    height: 7vh; /* Adjust this value as needed */
}
.btn-outline-black{
    --bs-btn-color:rgb(0, 0, 0);
    --bs-btn-border-color:rgb(3, 3, 3);
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg:rgb(0, 0, 0);
    --bs-btn-hover-border-color:rgb(0, 0, 0);
    --bs-btn-focus-shadow-rgb: 108, 117, 125;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg:rgb(0, 0, 0);
    --bs-btn-active-border-color:rgb(0, 0, 0);
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color:rgb(0, 0, 0);
    --bs-btn-disabled-bg: transparent;
}
</style>