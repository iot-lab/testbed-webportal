# -*- coding: utf-8 -*-

from fabric.api import local, run, put, lcd

def test():
    """ Run Web portal tests. """
    local('npm run test:unit')
    local('npm run test:e2e -- --headless')
    

def build(env='dev'):
    """ Build Web portal (to ./dist). """
    local('npm run build:%s' % env)
    
def deploy(dist='./dist', dest='testbed-webportal'):
    """ Deploy Web portal. """
    with lcd(dist):
        run('rm -Rf /tmp/devportal/')
        run('mkdir /tmp/devportal')
        put("*", "/tmp/devportal/")
    run('ssh srvwww \"rm -Rf /var/www/%s/*\"' % dest)
    run('ssh srvwww \"mkdir -p /var/www/%s\"' % dest)
    run('scp -r /tmp/devportal/* srvwww:/var/www/%s/' % dest)
    run('rm -Rf /tmp/devportal/')
