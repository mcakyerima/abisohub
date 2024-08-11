<style>
  /* Default styles for all screen sizes */
.containerr {
    max-width: 1200px; /* Adjust max-width according to your design */
    margin: 0 auto; /* Center the container horizontally */
    padding-bottom: 40px; /* Add some spacing around the content */
    padding-top: 0px;
}

/* For mobile devices */
@media (max-width: 768px) {
    .col-3 {
        width: 50%; /* Display two columns per row on mobile */
    }
    /* Add other mobile-specific styles here */
}

/* For tablets */
@media (min-width: 769px) and (max-width: 1024px) {
    .col-3 {
        width: 33.33%; /* Display three columns per row on tablets */
    }
    /* Add other tablet-specific styles here */
}

/* For PCs and larger screens */
@media (min-width: 1025px) {
    .col-3 {
        width: 25%; /* Display four columns per row on larger screens */
    }
    /* Add other PC-specific styles here */
}
</style>