<?php

return [
    'unauthorized' => 'Unauthorized',
    'one_or_more_error' => '(and :count more error)',
    'credentials_are_wrong' => 'The provided credentials does not match our records.',
    'price_not_found' => 'Price not found. Please contact us.',
    'error_when_processing' => 'An error occurred while processing the request.',
    'user_not_found' => 'User not found.',
    'subscription_not_found' => 'Subscription not found for given user.',
    'expired_at_range_error' => "The :attribute and renewed_at should be within 1 month. (':date_can_be' value can be used)",
    'field_is_required' => 'The :field field cannot be empty.',
    'user_registered_successfully' => 'User registered successfully. (id=:id)',
    'subscription_added_successfully' => 'Subscription added successfully for given user. (id=:id)',
    'subscription_updated_successfully' => 'Subscription updated for given user.',
    'field_not_match_date_format' => 'The :field does not match the Y-m-d format.',
    'subscription_deleted_successfully' => 'Subscription deleted for given user.',
    'subscriptions_deleted_successfully' => 'All subscriptions deleted for given user.',
    'subscription_renewed' => 'Subscription (:id) renewed for user :name',
    'transaction_added_successfully' => 'Payment added for the users subscription.',
];
