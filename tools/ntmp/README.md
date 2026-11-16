# NTMP landing page

The current implementation, build instructions, verification evidence and deployment requirements are documented in [RELEASE.md](RELEASE.md).

Rebuild: `python tools/ntmp/match_reference.py`

Backend checks: `python tools/ntmp/test_backend.py`

Browser checks: start `php -S 127.0.0.1:8126 -t .`, then run `python tools/ntmp/verify.py`.

Release ZIP: `python tools/ntmp/package_release.py`

No production deployment or real CRM delivery has been verified. Publishing requires the existing GitHub and PHP hosting connections.
