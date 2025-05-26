<?php

return [
    /**
     * API Token Key (string)
     * Accepted value:
     * Live Token: https://myfatoorah.readme.io/docs/live-token
     * Test Token: https://myfatoorah.readme.io/docs/test-token
     */
    // 'api_key' => 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL',
    'api_key' => '4pdKbltvxVklbzCNq2UnFqOl2SauDrwonNKPehJTIYczAhLQwCFamvb41HIdQNuCo_4dNUNovmNzlpnHoGS3NkaI68pN-_fUZvXo_tA4Y5c9gCVxuoYpR0NFXpOh-FYhGm8TRmMSrBPv6TmH8R4WdJBDG5n9SL2FoFS1DzxO0oorZ2chcQZt6-h5DR8j3LJ-q3WRQm_SEqRp-MzHSW2S_8G4K7Qg1cNIO_gc-1uvpadpzzAL3Ods4AJTQPYjKbl7K-NvYOCRAAl6i9mwDQeeYSOGNQeZa4AlJPgdhL3XLo__8XuyFQ_gZKfJmz2SxgHKlF10iZkx36gidysI-uvzPtuQo7GmobyVEMQmRH683kEPb3MQUXNiJjas16bnzw6E-N3uytvlDcd6Jxn9-teuJa-p32fqp-FJLYgRwBEBks0-9GXHhst_Wf-KxdLAatYxsO3GzO2ZtJ-EsrN948Va6on27RcScG7X7tOubm9NwtnxhcERU7r7HTK7fIQEdp7ICgaNOIi8Jd7ZFKf3x1boXvTC-enrhYEGjOsYhKPrWudmkOgTyMOQx90RwzeJhAnsFFuDour_Qst2-R4vKqGxT_TsTUCmXRNC28SmB-SGmcCyqOtlf1L1Ss6cgWNpKj7HgXcSvaEv3bNVlY4YKId3eD8GF6ZJJTiIf2BwbH6dVZaPT1Bj',
    /**
     * Test Mode (boolean)
     * Accepted value: true for the test mode or false for the live mode
     */
    'test_mode' => true,
    /**
     * Country ISO Code (string)
     * Accepted value: KWT, SAU, ARE, QAT, BHR, OMN, JOD, or EGY.
     */
    'country_iso' => 'KWT',
    /**
     * Save card (boolean)
     * Accepted value: true if you want to enable save card options.
     * You should contact your account manager to enable this feature in your MyFatoorah account as well.
     */
    'save_card' => false,
    /**
     * Webhook secret key (string)
     * Enable webhook on your MyFatoorah account setting then paste the secret key here.
     * The webhook link is: https://{example.com}/myfatoorah/webhook
     */
    'webhook_secret_key' => '',
    /**
     * Register Apple Pay (boolean)
     * Set it to true to show the Apple Pay on the checkout page.
     * First, verify your domain with Apple Pay before you set it to true.
     * You can either follow the steps here: https://docs.myfatoorah.com/docs/apple-pay#verify-your-domain-with-apple-pay or contact the MyFatoorah support team (tech@myfatoorah.com).
    */
    'register_apple_pay' => false
];
