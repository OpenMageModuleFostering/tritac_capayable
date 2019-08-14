<?php
class Tritac_CapayableApiClient_Enums_RefuseReason extends Tritac_CapayableApiClient_Enums_Enum{
    
    // Each shop has an order amount limit above which pay after delivery by Capayable is 
    // not available. AMOUNT_EXCEEDS_LIMIT (1) means the order amount is above this limit
    const AMOUNT_EXCEEDS_LIMIT = 1;

    // There is a limit on the unpaid sum of all orders with this shops or other shops using 
    // Capayable. BALANCE_EXCEEDS_LIMIT (2) means the order amount plus the balance 
    // of unpaid orders by the customer is above this limit.
    const BALANCE_EXEEDS_LIMIT = 2;

    // Capayable uses services for the actual credit check (B2C: EDR, B2B: CreditSafe). 
    // NOT_CREDITWORTHY (3) means the customer is not accepted for credit by this service. 
    const NOT_CREDITWORTHY = 3;

    // CREDITCHECK_UNAVAILABLE (4) means the extern credit check service is not be available.
    const CREDITCHECK_UNAVAILABLE = 4;

    // NOT_FOUND (5) means the corporation could not be found based on its name
    // and/or its address (B2B only).
    const NOT_FOUND = 5;
}
