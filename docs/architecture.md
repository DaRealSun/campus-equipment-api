# Planned architecture

Browser test page -> HTTP routes -> controllers -> authorization and loan service -> repository interfaces -> MySQL.

The loan service owns checkout/return rules; controllers translate HTTP inputs and outcomes; repositories hide persistence. `EquipmentRepository` and `LoanRepository` are injected dependencies. Stage 1 implements them with PDO; Stage 2 can use Eloquent adapters without changing domain rules. Keep interfaces small and add abstractions when there is a concrete need.

Proposed schema: users(id, name, email unique, password_hash, role); equipment(id, name, description); loans(id, user_id, equipment_id, borrowed_at, returned_at nullable). Availability derives from active loans. A checkout locks the equipment row in a transaction before checking for an active loan. Every checkout path must use that lock.

Proposed design-change demonstration: add a loan-duration policy and an optional due date after the baseline. Show the requirement, exact changed modules, unchanged callers, relevant principle, and measured regression results. This is a planned exercise, not completed evidence.
