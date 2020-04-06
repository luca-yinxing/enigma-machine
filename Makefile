RELEASE=1
VERSION=0
PKGREL=1

PACKAGE=lucagasperini-esame

DEBSRC=${PACKAGE}_${RELEASE}.${VERSION}-${PKGREL}.debian.tar.xz
BUILDDIR?=${PACKAGE}_${RELEASE}.${VERSION}
PKGSRC=${PACKAGE}_${RELEASE}.${VERSION}.orig.tar.gz

DESTDIR=
SRVDIR=${DESTDIR}/srv/esame

GITVERSION:=$(shell git rev-parse HEAD)

ARCH=all
DEB=${PACKAGE}_${RELEASE}.${VERSION}-${PKGREL}_${ARCH}.deb


SOURCES = \
	commento.php \
	db.php \
	edit-index.php \
	edit.php \
	enigma-include.php \
	enigma.php \
	error.php \
	index.php \
	install.php \
	login.php \
	materia.php \
	set.php \
	user.php \
	face.png \
	styles/xsoftware.css \
	img/1942-Tre-Popoli-Una-Guerra.jpg \
	img/alan-turing.jpg \
	img/macchina-enigma.jpg \
	img/robert-oppenheimer.jpg \
	img/soldati-italiani.jpg \
	img/vigenere-square.jpg \
	img/giuseppe-ungaretti.jpg

all:

.PHONY: deb
deb: ${DEB}
${DEB}:
	rm -rf ${BUILDDIR}
	rsync -a * ${BUILDDIR}
	cd ${BUILDDIR}; dpkg-buildpackage -b -us -uc
	lintian ${DEB}

install:
	install -d -m 755 ${SRVDIR}
	install -d -m 755 ${SRVDIR}/img
	install -d -m 755 ${SRVDIR}/styles
	for i in ${SOURCES}; do install -D -m 0644 $$i ${SRVDIR}/$$i; done

.PHONY: clean
clean:
	rm -rf *~ *.deb *.changes *.buildinfo ${BUILDDIR}

.PHONY: distclean
distclean: clean

.PHONY: dinstall
dinstall: ${DEB}
	dpkg -i ${DEB}
