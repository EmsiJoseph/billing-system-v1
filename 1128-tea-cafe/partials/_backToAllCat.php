<?php
echo '<style>
    .category-link {
        color: #333; /* Dark text for sufficient contrast */
        text-decoration: none; /* No underlining */
        font-size: 16px; /* Readable text size */
        padding: 12px 20px; /* Increased padding for a larger clickable area */
        border: none; /* No border to maintain a shapeless appearance */
        cursor: pointer; /* Cursor indicates clickable element */
        display: inline-flex; /* Flex to align icon and text */
        align-items: center; /* Vertical alignment */
        gap: 10px; /* Increased spacing between icon and text */
        transition: background-color 0.2s ease-in-out; /* Smooth transition for background */
    }

    .category-link:hover, .category-link:focus {
        text-decoration: none; /* Removing underline for a cleaner hover effect */
        // background-color: #eaeaea; /* Slight background on hover for feedback */
        border-radius: 4px; /* Optional: Adds subtle rounding to the hover background */
    }

    .category-link i {
        font-size: 20px; /* Icon size */
    }
</style>

<div>
    <a href="index.php" class="category-link active">
        <i class="fas fa-arrow-left"></i>
        <span>Back to categories</span>
    </a>
</div>

';
