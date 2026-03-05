📘 PRD — AI Campaign Agent Platform
(Laravel Production Build)

1️⃣ Product Overview
Product Name
Sidekick Campaign Intelligence Platform

Product Vision
Build a gamified marketing workforce platform that allows companies to deploy campaigns and manage a distributed network of creators/agents who execute social tasks and earn rewards.

The platform consists of:
Public registration website
Admin HQ dashboard
Agent / Creator application
AI assignment engine
Reward + payout system
Fraud detection system

Business Goal
Build scalable marketing army infrastructure
Enable campaign automation
Create creator economy ecosystem
Provide measurable ROI campaigns
Automate campaign workforce management

Target Users
Primary
Influencers / content creators
Sales agents
Brand promoters

Secondary
Marketing teams
Campaign managers
Operations team

2️⃣ Product Scope
2.1 Core Modules
🌐 Public Website
creator registration
social profile submission
referral onboarding
approval pipeline

🧑‍💼 Admin HQ Dashboard
campaign management
agent management
fraud monitoring
reward configuration
payment management
analytics
system settings

📱 Agent System
campaign marketplace
task workflow
wallet
rewards
VIP upgrade
performance tracking

🤖 AI Engine
campaign assignment
performance scoring
risk scoring

💰 Payment System
payout request
QR payment
wallet credit
transaction logs

3️⃣ User Roles
3.1 Creator / Agent
register account
complete tasks
submit proof
receive rewards
upgrade VIP

3.2 Admin
approve users
create campaigns
manage payouts
configure system rules

3.3 System (AI)
auto assign campaigns
fraud detection
performance scoring

4️⃣ Core User Flow
4.1 Registration Flow
User → Website Signup → Verification → Admin Approval → Agent Account Created

4.2 Campaign Flow
Admin creates campaign
→ Agent applies
→ Agent completes task
→ Proof submission
→ Verification
→ Reward issued

4.3 Payment Flow
Approval → Payout request → Payment gateway → Wallet update

5️⃣ Functional Requirements
5.1 Public Website
Features

Registration Form
name
phone
email
social profiles
follower count
referral code
highlight post
evidence upload

Requirements
validate social handles
prevent duplicate accounts
store approval queue

5.2 Admin Dashboard
User Management
view creators
approve registration
assign tier
flag account
view performance

Campaign Management
create campaign
define task
set reward
define follower requirement
VIP-only access
campaign budget

Task Verification
review submission
approve / reject
fraud risk display
auto verification

Reward Engine
EXP configuration
multiplier control
leaderboard reset

VIP Engine
eligibility rule
VIP approval
expiry management

Fraud Engine
duplicate IP detection
fake follower detection
risk scoring

Analytics Dashboard
engagement
ROI
cost tracking

Broadcast System
send announcements
send notifications

System Settings
tier rules
payout rules
AI weight configuration

5.3 Agent / Creator App
Campaign Marketplace
view campaigns
filter by platform
apply task
VIP campaign lock

Task Execution
instructions
proof submission
progress tracker

Wallet System
balance view
transaction history
claim reward

Gamification
EXP tracking
ranking
leaderboard

Referral System
referral code
referral count
VIP Upgrade
payment QR
VIP activation

6️⃣ Non-Functional Requirements
Performance
handle 3k+ concurrent agents
API response < 500ms

Security
JWT authentication
role-based access control
encrypted payment data

Scalability
horizontal scaling supported
queue-based processing

Reliability
99.9% uptime
audit logging

7️⃣ System Architecture
Architecture Pattern
Modular Monolith (Laravel)

8️⃣ Tech Stack (Laravel Production)
Backend
Core
Laravel 11
PHP 8.3
Laravel Sanctum (auth)
Laravel Queue (Redis)
Laravel Horizon

Database
PostgreSQL (recommended)
Redis (cache + queue)

Admin Panel
Laravel Filament OR Laravel Nova

Frontend
Public Website
Next.js OR Blade

Agent App
React / PWA

9️⃣ Database Requirements

Tables include:
users
campaigns
tasks
applications
submissions
approvals
wallet
payouts
fraud_logs
vip_membership
referral
exp_events
system_settings
(Aligned with ERD we built)

10️⃣ API Requirements
Authentication
POST /auth/register
POST /auth/login

Campaign
GET /campaigns
POST /campaign
POST /apply-task

Task
POST /submit-proof
GET /task-status

Wallet
GET /wallet
POST /claim-reward

Admin
POST /approve-user
POST /approve-task

11️⃣ Success Metrics
Platform KPIs
agent activation rate
task completion rate
campaign ROI
fraud rate
payout processing time

12️⃣ Future Roadmap
Phase 1 — MVP
registration
campaign marketplace
task submission
payout
admin dashboard

Phase 2
AI assignment engine
fraud automation
mobile app

Phase 3
brand marketplace
multi-company SaaS
global expansion